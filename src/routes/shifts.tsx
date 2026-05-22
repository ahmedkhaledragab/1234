import { useState, useEffect } from 'react';
import { getShifts, createShift, closeShift } from '../lib/repo';
import type { Shift } from '../lib/local-db';
import { toast } from 'sonner';
import { formatCurrency, formatDateTime } from '../lib/utils';
import { Clock, Play, Square } from 'lucide-react';

export function ShiftsPage() {
  const [shifts, setShifts] = useState<Shift[]>([]);

  useEffect(() => { load(); }, []);
  async function load() { setShifts(await getShifts()); }

  async function handleOpen() {
    const cash = prompt('كاش الافتتاح:');
    if (cash) {
      await createShift({ opening_cash: +cash });
      toast.success('تم فتح الوردية');
      load();
    }
  }

  async function handleClose(id: string) {
    const cash = prompt('كاش الإغلاق:');
    if (cash) {
      await closeShift(id, +cash);
      toast.success('تم إغلاق الوردية');
      load();
    }
  }

  const activeShift = shifts.find(s => !s.closed_at);

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">الورديات</h1>
        {!activeShift ? (
          <button onClick={handleOpen} className="btn-primary flex items-center gap-2"><Play size={16} /> فتح وردية</button>
        ) : (
          <button onClick={() => handleClose(activeShift.id)} className="btn-danger flex items-center gap-2"><Square size={16} /> إغلاق الوردية</button>
        )}
      </div>

      {activeShift && (
        <div className="card border-2 border-green-200 bg-green-50 mb-6">
          <div className="flex items-center gap-3">
            <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
            <span className="font-bold text-green-700">وردية نشطة</span>
          </div>
          <p className="text-sm text-gray-600 mt-2">بدأت: {formatDateTime(activeShift.opened_at)}</p>
          <p className="text-sm text-gray-600">كاش الافتتاح: {formatCurrency(activeShift.opening_cash)}</p>
        </div>
      )}

      <div className="card overflow-hidden">
        <table className="w-full">
          <thead><tr className="border-b border-gray-100">
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الافتتاح</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الإغلاق</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">كاش فتح</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">كاش إغلاق</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الحالة</th>
          </tr></thead>
          <tbody>
            {shifts.map(s => (
              <tr key={s.id} className="border-b border-gray-50">
                <td className="py-3 px-4 text-sm">{formatDateTime(s.opened_at)}</td>
                <td className="py-3 px-4 text-sm">{s.closed_at ? formatDateTime(s.closed_at) : '—'}</td>
                <td className="py-3 px-4 text-sm">{formatCurrency(s.opening_cash)}</td>
                <td className="py-3 px-4 text-sm">{s.closing_cash != null ? formatCurrency(s.closing_cash) : '—'}</td>
                <td className="py-3 px-4"><span className={`badge ${s.closed_at ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700'}`}>{s.closed_at ? 'مغلقة' : 'نشطة'}</span></td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
