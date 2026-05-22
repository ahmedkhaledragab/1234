// QZ Tray integration for thermal printing (ESC/POS)
// Requires QZ Tray to be installed on the machine

let qzInstance: any = null;

export async function connectQZ(): Promise<boolean> {
  try {
    const qz = (window as any).qz;
    if (!qz) {
      console.warn('QZ Tray not loaded');
      return false;
    }
    if (!qz.websocket.isActive()) {
      await qz.websocket.connect();
    }
    qzInstance = qz;
    return true;
  } catch (err) {
    console.error('QZ connection error:', err);
    return false;
  }
}

export async function getPrinters(): Promise<string[]> {
  if (!qzInstance) await connectQZ();
  if (!qzInstance) return [];
  try {
    return await qzInstance.printers.find();
  } catch {
    return [];
  }
}
