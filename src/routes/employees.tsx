import { useState, useEffect } from 'react';
import { getEmployees, createEmployee } from '../lib/repo';
import type { Employee } from '../lib/local-db';
import { toast } from 'sonner';
import { Plus, UserCog, Phone } from 'lucide-react';
import { formatCurrency } from '../lib/utils';

export function EmployeesPage() {
  const [employees, setEmployees] = useState<Employee[]>([]);
  const [showAdd, setShowAdd] = useState(false);
  const [form, setForm] = useState({ name: '', phone: '', role: 'كاشير', salary: 0, is_active: true });

  useEffect(() => { load(); }, []);
  async function load() { setEmployees(await getEmployees()); }

  async function handleAdd(e: React.FormEvent) {
    e.preventDefault();
    await createEmployee(form);
    toast.success('تم إضافة الموظف');
    setShowAdd(false);
    load();
  }

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">الموظفين</h1>
        <button onClick={() => setShowAdd(true)} className="btn-primary flex items-center gap-2"><Plus size={16} /> موظف جديد</button>
      </div>
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {employees.map(emp => (
          <div key={emp.id} className="card">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center"><UserCog size={20} className="text-primary-600" /></div>
              <div>
                <h3 className="font-bold text-sm">{emp.name}</h3>
                <p className="text-xs text-gray-500">{emp.role}</p>
              </div>
            </div>
            <div className="mt-3 flex justify-between text-xs text-gray-500">
              {emp.phone && <span className="flex items-center gap-1"><Phone size={10} />{emp.phone}</span>}
              <span>الراتب: {formatCurrency(emp.salary)}</span>
            </div>
          </div>
        ))}
      </div>

      {showAdd && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-sm">
            <h2 className="text-lg font-bold mb-4">موظف جديد</h2>
            <form onSubmit={handleAdd} className="space-y-4">
              <input type="text" placeholder="الاسم" value={form.name} onChange={e => setForm({...form, name: e.target.value})} className="input-field" required />
              <input type="tel" placeholder="الهاتف" value={form.phone} onChange={e => setForm({...form, phone: e.target.value})} className="input-field" />
              <input type="text" placeholder="الوظيفة" value={form.role} onChange={e => setForm({...form, role: e.target.value})} className="input-field" />
              <input type="number" placeholder="الراتب" value={form.salary || ''} onChange={e => setForm({...form, salary: +e.target.value})} className="input-field" />
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
