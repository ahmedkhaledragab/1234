export interface ReceiptSettings {
  storeName: string;
  storeAddress: string;
  storePhone: string;
  taxRate: number;
  taxLabel: string;
  showTax: boolean;
  printerName: string;
  paperSize: '80mm' | '58mm';
  showLogo: boolean;
  logoUrl: string;
  footerText: string;
  autoPrint: boolean;
}

const DEFAULT_SETTINGS: ReceiptSettings = {
  storeName: 'ستي',
  storeAddress: '',
  storePhone: '',
  taxRate: 14,
  taxLabel: 'ضريبة القيمة المضافة',
  showTax: true,
  printerName: '',
  paperSize: '80mm',
  showLogo: false,
  logoUrl: '',
  footerText: 'شكراً لزيارتكم',
  autoPrint: false,
};

const STORAGE_KEY = 'city_receipt_settings';

export function getReceiptSettings(): ReceiptSettings {
  const saved = localStorage.getItem(STORAGE_KEY);
  if (saved) {
    return { ...DEFAULT_SETTINGS, ...JSON.parse(saved) };
  }
  return DEFAULT_SETTINGS;
}

export function saveReceiptSettings(s: Partial<ReceiptSettings>) {
  const current = getReceiptSettings();
  const updated = { ...current, ...s };
  localStorage.setItem(STORAGE_KEY, JSON.stringify(updated));
  return updated;
}
