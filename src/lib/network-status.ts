import { create } from 'zustand';

interface NetworkState {
  isOnline: boolean;
  lastSyncAt: string | null;
  isSyncing: boolean;
  setOnline: (online: boolean) => void;
  setLastSync: (date: string) => void;
  setSyncing: (syncing: boolean) => void;
}

export const useNetworkStore = create<NetworkState>((set) => ({
  isOnline: navigator.onLine,
  lastSyncAt: null,
  isSyncing: false,
  setOnline: (online) => set({ isOnline: online }),
  setLastSync: (date) => set({ lastSyncAt: date }),
  setSyncing: (syncing) => set({ isSyncing: syncing }),
}));

// Listen to browser online/offline events
if (typeof window !== 'undefined') {
  window.addEventListener('online', () => {
    useNetworkStore.getState().setOnline(true);
  });
  window.addEventListener('offline', () => {
    useNetworkStore.getState().setOnline(false);
  });
}
