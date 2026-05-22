import { db, type OutboxEntry } from './local-db';
import { supabase } from './supabase';
import { useNetworkStore } from './network-status';

const SYNC_INTERVAL = 60000; // 60 seconds
let syncTimer: ReturnType<typeof setInterval> | null = null;

// Tables to sync from cloud
const SYNC_TABLES = [
  'categories',
  'products',
  'product_variants',
  'modifiers',
  'customers',
  'orders',
  'order_items',
  'order_item_modifiers',
  'restaurant_tables',
  'delivery_zones',
  'coupons',
  'ingredients',
  'recipes',
  'employees',
  'shifts',
  'expenses',
  'expense_categories',
] as const;

// Push outbox entries to Supabase
async function pushOutbox(): Promise<void> {
  const entries = await db.outbox.toArray();
  if (entries.length === 0) return;

  for (const entry of entries) {
    try {
      let result;
      switch (entry.operation) {
        case 'insert':
          result = await supabase.from(entry.table_name).insert(entry.payload);
          break;
        case 'update':
          result = await supabase.from(entry.table_name).update(entry.payload).eq('id', entry.record_id);
          break;
        case 'delete':
          result = await supabase.from(entry.table_name).delete().eq('id', entry.record_id);
          break;
      }

      if (result?.error) {
        console.error(`Sync error for ${entry.table_name}:`, result.error);
        // Increment retries
        await db.outbox.update(entry.id!, { retries: entry.retries + 1 });
        // Skip if too many retries
        if (entry.retries >= 5) {
          await db.outbox.delete(entry.id!);
        }
      } else {
        // Success - remove from outbox
        await db.outbox.delete(entry.id!);
      }
    } catch (err) {
      console.error('Push error:', err);
    }
  }
}

// Pull all data from Supabase to local
async function pullAll(): Promise<void> {
  for (const tableName of SYNC_TABLES) {
    try {
      const { data, error } = await supabase.from(tableName).select('*');
      if (error) {
        console.error(`Pull error for ${tableName}:`, error);
        continue;
      }
      if (data && data.length > 0) {
        const table = (db as any)[tableName];
        if (table) {
          await table.clear();
          await table.bulkPut(data);
        }
      }
    } catch (err) {
      console.error(`Pull exception for ${tableName}:`, err);
    }
  }
}

// Full sync cycle
export async function syncNow(): Promise<void> {
  const { isOnline, setSyncing, setLastSync } = useNetworkStore.getState();
  if (!isOnline) return;

  setSyncing(true);
  try {
    await pushOutbox();
    await pullAll();
    setLastSync(new Date().toISOString());
  } catch (err) {
    console.error('Sync failed:', err);
  } finally {
    setSyncing(false);
  }
}

// Start periodic sync
export function startSync(): void {
  if (syncTimer) return;
  syncNow(); // Initial sync
  syncTimer = setInterval(syncNow, SYNC_INTERVAL);
}

// Stop periodic sync
export function stopSync(): void {
  if (syncTimer) {
    clearInterval(syncTimer);
    syncTimer = null;
  }
}

// Add to outbox (for offline writes)
export async function addToOutbox(
  tableName: string,
  operation: OutboxEntry['operation'],
  recordId: string,
  payload: any
): Promise<void> {
  await db.outbox.add({
    table_name: tableName,
    operation,
    record_id: recordId,
    payload,
    created_at: new Date().toISOString(),
    retries: 0,
  });

  // Try to sync immediately if online
  const { isOnline } = useNetworkStore.getState();
  if (isOnline) {
    pushOutbox().catch(console.error);
  }
}
