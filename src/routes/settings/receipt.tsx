import { useState } from 'react';
import { getReceiptSettings, saveReceiptSettings, type ReceiptSettings } from '../../lib/receipt-settings';
import { toast } from 'sonner';
import { Save, Printer } from 'lucide-react';

export function SettingsReceiptPage() {
  const [settings, setSettings] = useState<ReceiptSettings>(getReceiptSettings());

  function handleSave() {
    saveReceiptSettings(settings);
    toast.success('تم حفظ الإعدادات');
  }

  function update(key: keyof ReceiptSettings, value: any) {
    setSettings({ ...settings, [key]: value });
  }

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">إعدادات الفاتورة</h1>
        <button onClick={handleSave} className="btn-primary flex items-center gap-2"><Save size={16} /> حفظ</button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="card space-y-4">
          <h3 className="font-bold flex items-center gap-2"><Printer size={18} /> معلومات المحل</h3>
          <div><label className="text-sm text-gray-500">اسم المحل</label><input type="text" value={settings.storeName} onChange={e => update('storeName', e.target.value)} className="input-field mt-1" /></div>
          <div><label className="text-sm text-gray-500">العنوان</label><input type="text" value={settings.storeAddress} onChange={e => update('storeAddress', e.target.value)} className="input-field mt-1" /></div>
          <div><label className="text-sm text-gray-500">الهاتف</label><input type="tel" value={settings.storePhone} onChange={e => update('storePhone', e.target.value)} className="input-field mt-1" /></div>
          <div><label className="text-sm text-gray-500">نص الفوتر</label><input type="text" value={settings.footerText} onChange={e => update('footerText', e.target.value)} className="input-field mt-1" /></div>
        </div>

        <div className="card space-y-4">
          <h3 className="font-bold">إعدادات الضريبة والطباعة</h3>
          <div className="grid grid-cols-2 gap-3">
            <div><label className="text-sm text-gray-500">نسبة الضريبة %</label><input type="number" value={settings.taxRate} onChange={e => update('taxRate', +e.target.value)} className="input-field mt-1" /></div>
            <div><label className="text-sm text-gray-500">مسمى الضريبة</label><input type="text" value={settings.taxLabel} onChange={e => update('taxLabel', e.target.value)} className="input-field mt-1" /></div>
          </div>
          <div><label className="text-sm text-gray-500">اسم الطابعة (QZ Tray)</label><input type="text" value={settings.printerName} onChange={e => update('printerName', e.target.value)} className="input-field mt-1" /></div>
          <div>
            <label className="text-sm text-gray-500">مقاس الورق</label>
            <select value={settings.paperSize} onChange={e => update('paperSize', e.target.value)} className="input-field mt-1">
              <option value="80mm">80mm</option>
              <option value="58mm">58mm</option>
            </select>
          </div>
          <div className="flex items-center justify-between">
            <span className="text-sm">طباعة تلقائية عند إنشاء الطلب</span>
            <input type="checkbox" checked={settings.autoPrint} onChange={e => update('autoPrint', e.target.checked)} className="w-5 h-5 rounded" />
          </div>
          <div className="flex items-center justify-between">
            <span className="text-sm">إظهار الضريبة</span>
            <input type="checkbox" checked={settings.showTax} onChange={e => update('showTax', e.target.checked)} className="w-5 h-5 rounded" />
          </div>
        </div>
      </div>
    </div>
  );
}
