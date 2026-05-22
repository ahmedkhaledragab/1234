import { useState, useEffect } from 'react';
import { getCoupons } from '../lib/repo';
import { db } from '../lib/local-db';
import type { Coupon } from '../lib/local-db';
import { toast } from 'sonner';
import { formatCurrency, formatDate } from '../lib/utils';
import { Plus, Ticket, Copy } from 'lucide-react';

export function CouponsPage() {
  const [coupons, setCoupons] = useState<Coupon[]>([]);
  const [showAdd, setShowAdd] = useState(false);
  const [form, setForm] = useState({ code: '', discount_type: 'percentage' as 'percentage' | 'fixed', discount_value: 0, min_order_amount: 0, max_uses: 0, expires_at: '' });

  useEffect(() => { load(); }, []);
  async function load() { setCoupons(await getCoupons()); }

  async function handleAdd(e: React.FormEvent) {
    e.preventDefault();
    const id = crypto.randomUUID();
    const record: Coupon = { ...form, id, current_uses: 0, is_active: true, expires_at: form.expires_at || undefined };
    await db.coupons.put(record);
    toast.success('تم إضافة الكوبون');
    setShowAdd(false);
    setForm({ code: '', discount_type: 'percentage', discount_value: 0, min_order_amount: 0, max_uses: 0, expires_at: '' });
    load();
  }

  function copyCode(code: string) {
    navigator.clipboard.writeText(code);
    toast.success('تم نسخ الكود');
  }

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">الكوبونات</h1>
        <button onClick={() => setShowAdd(true)} className="btn-primary flex items-center gap-2"><Plus size={16} /> كوبون جديد</button>
      </div>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {coupons.map(c => (
          <div key={c.id} className="card">
            <div className="flex items-center justify-between mb-2">
              <div className="flex items-center gap-2"><Ticket size={16} className="text-primary-500" /><span className="font-mono font-bold">{c.code}</span></div>
              <button onClick={() => copyCode(c.code)} className="text-gray-400 hover:text-gray-600"><Copy size={14} /></button>
            </div>
            <p className="text-sm text-gray-600">{c.discount_type === 'percentage' ? `${c.discount_value}%` : formatCurrency(c.discount_value)} خصم</p>
            <div className="flex justify-between text-xs text-gray-500 mt-2">
              <span>استخدام: {c.current_uses}/{c.max_uses || '∞'}</span>
              {c.expires_at && <span>ينتهي: {formatDate(c.expires_at)}</span>}
            </div>
          </div>
        ))}
        {coupons.length === 0 && <div className="col-span-full text-center py-8 text-gray-400">لا توجد كوبونات</div>}
      </div>

      {showAdd && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-sm">
            <h2 className="text-lg font-bold mb-4">كوبون جديد</h2>
            <form onSubmit={handleAdd} className="space-y-4">
              <input type="text" placeholder="كود الكوبون" value={form.code} onChange={e => setForm({...form, code: e.target.value.toUpperCase()})} className="input-field font-mono" required />
              <select value={form.discount_type} onChange={e => setForm({...form, discount_type: e.target.value as any})} className="input-field">
                <option value="percentage">نسبة مئوية %</option>
                <option value="fixed">مبلغ ثابت</option>
              </select>
              <input type="number" placeholder="قيمة الخصم" value={form.discount_value || ''} onChange={e => setForm({...form, discount_value: +e.target.value})} className="input-field" required />
              <input type="number" placeholder="الحد الأقصى للاستخدام (0 = لا محدود)" value={form.max_uses || ''} onChange={e => setForm({...form, max_uses: +e.target.value})} className="input-field" />
              <input type="date" value={form.expires_at} onChange={e => setForm({...form, expires_at: e.target.value})} className="input-field" />
              <div className="flex gap-3">
                <button type="submit" className="btn-primary flex-1">إضافة</button>
                <button type="button" onClick={() => setShowAdd(false)} className="btn-secondary flex-1">إلغاء</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
