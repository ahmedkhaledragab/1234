// QZ Tray integration for thermal printing (ESC/POS)
// Requires QZ Tray to be installed on the machine

import { getReceiptSettings } from './receipt-settings';

let qz: any = null;

function getQZ() {
  if (!qz) {
    qz = (window as any).qz;
  }
  return qz;
}

export async function connectQZ(): Promise<boolean> {
  try {
    const qzTray = getQZ();
    if (!qzTray) {
      console.warn('QZ Tray not loaded. Make sure QZ Tray is installed and running.');
      return false;
    }
    if (!qzTray.websocket.isActive()) {
      await qzTray.websocket.connect();
    }
    return true;
  } catch (err) {
    console.error('QZ connection error:', err);
    return false;
  }
}

export async function disconnectQZ(): Promise<void> {
  const qzTray = getQZ();
  if (qzTray && qzTray.websocket.isActive()) {
    await qzTray.websocket.disconnect();
  }
}

export async function getPrinters(): Promise<string[]> {
  const connected = await connectQZ();
  if (!connected) return [];
  try {
    return await getQZ().printers.find();
  } catch {
    return [];
  }
}

export async function getDefaultPrinter(): Promise<string | null> {
  const connected = await connectQZ();
  if (!connected) return null;
  try {
    return await getQZ().printers.getDefault();
  } catch {
    return null;
  }
}

// Print receipt using ESC/POS commands
export async function printReceipt(orderData: {
  orderNumber: number;
  orderType: string;
  items: { name: string; quantity: number; price: number; modifiers?: string[] }[];
  subtotal: number;
  discount: number;
  total: number;
  notes?: string;
  customerName?: string;
  tableName?: string;
}): Promise<boolean> {
  const connected = await connectQZ();
  if (!connected) {
    console.error('Cannot print: QZ Tray not connected');
    return false;
  }

  const settings = getReceiptSettings();
  const printerName = settings.printerName;

  if (!printerName) {
    console.error('No printer configured');
    return false;
  }

  try {
    const qzTray = getQZ();
    const config = qzTray.configs.create(printerName);

    // Build ESC/POS data
    const data: any[] = [];
    const ESC = '\x1B';
    const GS = '\x1D';
    const CENTER = ESC + '\x61\x01';
    const LEFT = ESC + '\x61\x00';
    const BOLD_ON = ESC + '\x45\x01';
    const BOLD_OFF = ESC + '\x45\x00';
    const DOUBLE_HEIGHT = GS + '\x21\x11';
    const NORMAL_SIZE = GS + '\x21\x00';
    const CUT = GS + '\x56\x00';
    const DRAWER = ESC + '\x70\x00\x19\xFA';
    const LINE = '--------------------------------\n';

    // Header
    data.push(CENTER);
    data.push(BOLD_ON);
    data.push(DOUBLE_HEIGHT);
    data.push(settings.storeName + '\n');
    data.push(NORMAL_SIZE);
    data.push(BOLD_OFF);
    if (settings.storeAddress) data.push(settings.storeAddress + '\n');
    if (settings.storePhone) data.push(settings.storePhone + '\n');
    data.push(LINE);

    // Order info
    data.push(LEFT);
    data.push(BOLD_ON);
    data.push(`طلب #${orderData.orderNumber}\n`);
    data.push(BOLD_OFF);

    const typeLabels: Record<string, string> = {
      dine_in: 'صالة',
      takeaway: 'تيك أواي',
      delivery: 'توصيل',
      orders: 'طلبات',
    };
    data.push(`النوع: ${typeLabels[orderData.orderType] || orderData.orderType}\n`);
    if (orderData.tableName) data.push(`طاولة: ${orderData.tableName}\n`);
    if (orderData.customerName) data.push(`عميل: ${orderData.customerName}\n`);
    data.push(`التاريخ: ${new Date().toLocaleString('ar-EG')}\n`);
    data.push(LINE);

    // Items
    data.push(BOLD_ON);
    data.push('الأصناف:\n');
    data.push(BOLD_OFF);
    for (const item of orderData.items) {
      const itemTotal = item.price * item.quantity;
      data.push(`${item.quantity}x ${item.name}\n`);
      data.push(`   ${itemTotal.toFixed(2)} ج.م\n`);
      if (item.modifiers && item.modifiers.length > 0) {
        for (const mod of item.modifiers) {
          data.push(`   + ${mod}\n`);
        }
      }
    }
    data.push(LINE);

    // Totals
    data.push(`المجموع: ${orderData.subtotal.toFixed(2)} ج.م\n`);
    if (orderData.discount > 0) {
      data.push(`الخصم: -${orderData.discount.toFixed(2)} ج.م\n`);
    }
    if (settings.showTax && settings.taxRate > 0) {
      const tax = orderData.total * (settings.taxRate / 100);
      data.push(`${settings.taxLabel} (${settings.taxRate}%): ${tax.toFixed(2)} ج.م\n`);
    }
    data.push(BOLD_ON);
    data.push(DOUBLE_HEIGHT);
    data.push(`الإجمالي: ${orderData.total.toFixed(2)} ج.م\n`);
    data.push(NORMAL_SIZE);
    data.push(BOLD_OFF);

    // Notes
    if (orderData.notes) {
      data.push(LINE);
      data.push(`ملاحظات: ${orderData.notes}\n`);
    }

    // Footer
    data.push(LINE);
    data.push(CENTER);
    data.push(settings.footerText + '\n');
    data.push('\n\n\n');

    // Cut paper
    data.push(CUT);

    // Open cash drawer
    data.push(DRAWER);

    await qzTray.print(config, [{ type: 'raw', format: 'plain', data: data.join('') }]);
    return true;
  } catch (err) {
    console.error('Print error:', err);
    return false;
  }
}

// Print kitchen ticket
export async function printKitchenTicket(orderData: {
  orderNumber: number;
  orderType: string;
  items: { name: string; quantity: number; modifiers?: string[]; notes?: string }[];
  tableName?: string;
  notes?: string;
}): Promise<boolean> {
  const connected = await connectQZ();
  if (!connected) return false;

  const settings = getReceiptSettings();
  const printerName = settings.printerName; // Could use a separate kitchen printer setting

  if (!printerName) return false;

  try {
    const qzTray = getQZ();
    const config = qzTray.configs.create(printerName);

    const ESC = '\x1B';
    const GS = '\x1D';
    const CENTER = ESC + '\x61\x01';
    const LEFT = ESC + '\x61\x00';
    const BOLD_ON = ESC + '\x45\x01';
    const BOLD_OFF = ESC + '\x45\x00';
    const DOUBLE_HEIGHT = GS + '\x21\x11';
    const NORMAL_SIZE = GS + '\x21\x00';
    const CUT = GS + '\x56\x00';
    const LINE = '================================\n';

    const data: string[] = [];

    // Header
    data.push(CENTER);
    data.push(BOLD_ON);
    data.push(DOUBLE_HEIGHT);
    data.push('--- مطبخ ---\n');
    data.push(NORMAL_SIZE);
    data.push(BOLD_OFF);
    data.push(LINE);

    // Order info
    data.push(LEFT);
    data.push(BOLD_ON);
    data.push(DOUBLE_HEIGHT);
    data.push(`طلب #${orderData.orderNumber}\n`);
    data.push(NORMAL_SIZE);
    data.push(BOLD_OFF);

    const typeLabels: Record<string, string> = {
      dine_in: 'صالة', takeaway: 'تيك أواي', delivery: 'توصيل', orders: 'طلبات'
    };
    data.push(`${typeLabels[orderData.orderType] || orderData.orderType}`);
    if (orderData.tableName) data.push(` | طاولة: ${orderData.tableName}`);
    data.push('\n');
    data.push(`${new Date().toLocaleTimeString('ar-EG')}\n`);
    data.push(LINE);

    // Items (large text for kitchen)
    for (const item of orderData.items) {
      data.push(BOLD_ON);
      data.push(DOUBLE_HEIGHT);
      data.push(`${item.quantity}x ${item.name}\n`);
      data.push(NORMAL_SIZE);
      data.push(BOLD_OFF);
      if (item.modifiers && item.modifiers.length > 0) {
        for (const mod of item.modifiers) {
          data.push(`  + ${mod}\n`);
        }
      }
      if (item.notes) {
        data.push(`  * ${item.notes}\n`);
      }
    }

    // Notes
    if (orderData.notes) {
      data.push(LINE);
      data.push(BOLD_ON);
      data.push(`ملاحظات: ${orderData.notes}\n`);
      data.push(BOLD_OFF);
    }

    data.push('\n\n\n');
    data.push(CUT);

    await qzTray.print(config, [{ type: 'raw', format: 'plain', data: data.join('') }]);
    return true;
  } catch (err) {
    console.error('Kitchen print error:', err);
    return false;
  }
}

// Open cash drawer
export async function openCashDrawer(): Promise<boolean> {
  const connected = await connectQZ();
  if (!connected) return false;

  const settings = getReceiptSettings();
  if (!settings.printerName) return false;

  try {
    const qzTray = getQZ();
    const config = qzTray.configs.create(settings.printerName);
    const DRAWER = '\x1B\x70\x00\x19\xFA';
    await qzTray.print(config, [{ type: 'raw', format: 'plain', data: DRAWER }]);
    return true;
  } catch {
    return false;
  }
}
