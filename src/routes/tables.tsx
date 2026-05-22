import { useState, useEffect } from 'react';
import { getTables, createTable, updateTable } from '../lib/repo';
import type { RestaurantTable } from '../lib/local-db';
import { toast } from 'sonner';
import { Plus, Users } from 'lucide-react';

export function TablesPage() {
  const [tables, setTables] = useState<RestaurantTable[]>([]);

  useEffect(() => { loadTables(); }, []);
  async function loadTables() { setTables(await getTables()); }

  async function handleAdd() {
    const name = prompt('اسم / رقم الطاولة:');
    if (name) {
      await createTable({ name, capacity: 4, status: 'available' });
      toast.success('تم إضافة الطاولة');
      loadTables();
    }
  }

  async function toggleStatus(table: RestaurantTable) {
    const next = table.status === 'available' ? 'occupied' : 'available';
    await updateTable(table.id, { status: next });
    loadTables();
  }

  const statusColors: Record<string, string> = {
    available: 'bg-green-100 border-green-300 text-green-700',
    occupied: 'bg-red-100 border-red-300 text-red-700',
    reserved: 'bg-yellow-100 border-yellow-300 text-yellow-700',
  };
  const statusLabels: Record<string, string> = { available: 'متاحة', occupied: 'مشغولة', reserved: 'محجوزة' };

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">الطاولات</h1>
        <button onClick={handleAdd} className="btn-primary flex items-center gap-2"><Plus size={16} /> إضافة طاولة</button>
      </div>
      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        {tables.map(table => (
          <button key={table.id} onClick={() => toggleStatus(table)} className={`card border-2 text-center cursor-pointer hover:shadow-md transition-shadow ${statusColors[table.status]}`}>
            <Users size={24} className="mx-auto mb-2" />
            <p className="font-bold">{table.name}</p>
            <p className="text-xs mt-1">{statusLabels[table.status]}</p>
            <p className="text-xs text-gray-500">{table.capacity} أشخاص</p>
          </button>
        ))}
      </div>
    </div>
  );
}
