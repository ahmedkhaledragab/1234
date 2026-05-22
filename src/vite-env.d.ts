/// <reference types="vite/client" />

interface Window {
  electronAPI?: {
    getAppVersion: () => Promise<string>;
    getAppPath: () => Promise<string>;
    platform: string;
    isElectron: boolean;
  };
}
