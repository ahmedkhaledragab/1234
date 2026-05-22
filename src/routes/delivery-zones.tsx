import { useState, useEffect } from 'react';
import { getDeliveryZones, createDeliveryZone } from '../lib/repo';
import type { DeliveryZone } from '../lib/local-db';
import { toast } from 'sonner';
import { formatCurrency } from '../lib/utils';
import { Plus, MapPin } from 'lucide-react';

export function DeliveryZonesPage() {
  const [zones, setZones] = useState<DeliveryZone[]>([]);
  const [showAdd, setShowAdd] = useState(false);
  const [form, setForm] = useState({ name: '', delivery_fee: 0, estimated_time: '', is_active: true });

  useEffect(() => { load(); }, []);
  async function load() { setZones(await getDeliveryZones()); }

  async function handleAdd(e: React.FormEvent) {
    e.preventDefault();
    await createDeliveryZone(form);
    toast.success('تم إضافة المنطقة');
    setShowAdd(false);
    setForm({ name: '', delivery_fee: 0, estimated_time: '', is_active: true });
    load();
  }

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">مناطق التوصيل</h1>
        <button onClick={() => setShowAdd(true)} className="btn-primary flex items-center gap-2"><Plus size={16} /> منطقة جديدة</button>
      </div>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {zones.map(z => (
          <div key={z.id} className="card flex items-center gap-3">
            <div className="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center"><MapPin size={20} className="text-blue-600" /></div>
            <div className="flex-1">
              <h3 className="font-bold text-sm">{z.name}</h3>
              <p className="text-xs text-gray-500">{z.estimated_time || '—'}</p>
            </div>
            <span className="font-bold text-primary-600">{formatCurrency(z.delivery_fee)}</span>
          </div>
        ))}
        {zones.length === 0 && <div className="col-span-full text-center py-8 text-gray-400">لا توجد مناطق توصيل</div>}
      </div>

      {showAdd && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-sm">
            <h2 className="text-lg font-bold mb-4">منطقة توصيل جديدة</h2>
            <form onSubmit={handleAdd} className="space-y-4">
              <input type="text" placeholder="اسم المنطقة" value={form.name} onChange={e => setForm({...form, name: e.target.value})} className="input-field" required />
              <input type="number" placeholder="سعر التوصيل" value={form.delivery_fee || ''} onChange={e => setForm({...form, delivery_fee: +e.target.value})} className="input-field" required />
              <input type="text" placeholder="الوقت المتوقع (مثلاً: 30 دقيقة)" value={form.estimated_time} onChange={e => setForm({...form, estimated_time: e.target.value})} className="input-field" />
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
