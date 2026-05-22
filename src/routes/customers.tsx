import { useState, useEffect } from 'react';
import { getCustomers, createCustomer } from '../lib/repo';
import type { Customer } from '../lib/local-db';
import { toast } from 'sonner';
import { Plus, Phone, Star, Search } from 'lucide-react';
import { formatCurrency } from '../lib/utils';

export function CustomersPage() {
  const [customers, setCustomers] = useState<Customer[]>([]);
  const [search, setSearch] = useState('');
  const [showAdd, setShowAdd] = useState(false);
  const [form, setForm] = useState({ name: '', phone: '', address: '' });

  useEffect(() => { load(); }, []);
  async function load() { setCustomers(await getCustomers()); }

  async function handleAdd(e: React.FormEvent) {
    e.preventDefault();
    await createCustomer(form);
    toast.success('تم إضافة العميل');
    setShowAdd(false);
    setForm({ name: '', phone: '', address: '' });
    load();
  }

  const filtered = search ? customers.filter(c => c.name.includes(search) || c.phone?.includes(search)) : customers;

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">العملاء</h1>
        <button onClick={() => setShowAdd(true)} className="btn-primary flex items-center gap-2"><Plus size={16} /> عميل جديد</button>
      </div>

      <div className="relative max-w-sm mb-6">
        <Search size={16} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" />
        <input type="text" placeholder="بحث بالاسم أو الهاتف..." value={search} onChange={e => setSearch(e.target.value)} className="input-field pr-10" />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {filtered.map(c => (
          <div key={c.id} className="card">
            <div className="flex items-start justify-between mb-2">
              <h3 className="font-bold">{c.name}</h3>
              <div className="flex items-center gap-1 text-yellow-500"><Star size={14} /><span className="text-xs">{c.loyalty_points}</span></div>
            </div>
            {c.phone && <p className="text-sm text-gray-500 flex items-center gap-1"><Phone size={12} />{c.phone}</p>}
            <div className="mt-3 flex justify-between text-xs text-gray-500">
              <span>{c.total_orders} طلب</span>
              <span>أنفق {formatCurrency(c.total_spent)}</span>
            </div>
          </div>
        ))}
      </div>

      {showAdd && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-sm">
            <h2 className="text-lg font-bold mb-4">عميل جديد</h2>
            <form onSubmit={handleAdd} className="space-y-4">
              <input type="text" placeholder="الاسم" value={form.name} onChange={e => setForm({...form, name: e.target.value})} className="input-field" required />
              <input type="tel" placeholder="الهاتف" value={form.phone} onChange={e => setForm({...form, phone: e.target.value})} className="input-field" />
              <input type="text" placeholder="العنوان" value={form.address} onChange={e => setForm({...form, address: e.target.value})} className="input-field" />
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
