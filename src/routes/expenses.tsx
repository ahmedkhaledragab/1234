import { useState, useEffect } from 'react';
import { getExpenses, createExpense, getExpenseCategories } from '../lib/repo';
import type { Expense, ExpenseCategory } from '../lib/local-db';
import { toast } from 'sonner';
import { formatCurrency, formatDate } from '../lib/utils';
import { Plus, Wallet } from 'lucide-react';

export function ExpensesPage() {
  const [expenses, setExpenses] = useState<Expense[]>([]);
  const [categories, setCategories] = useState<ExpenseCategory[]>([]);
  const [showAdd, setShowAdd] = useState(false);
  const [form, setForm] = useState({ description: '', amount: 0, category_id: '', date: new Date().toISOString().split('T')[0] });

  useEffect(() => { load(); }, []);
  async function load() {
    const [exps, cats] = await Promise.all([getExpenses(), getExpenseCategories()]);
    setExpenses(exps);
    setCategories(cats);
  }

  async function handleAdd(e: React.FormEvent) {
    e.preventDefault();
    await createExpense(form);
    toast.success('تم إضافة المصروف');
    setShowAdd(false);
    setForm({ description: '', amount: 0, category_id: '', date: new Date().toISOString().split('T')[0] });
    load();
  }

  const total = expenses.reduce((s, e) => s + e.amount, 0);

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">المصروفات</h1>
        <button onClick={() => setShowAdd(true)} className="btn-primary flex items-center gap-2"><Plus size={16} /> مصروف جديد</button>
      </div>

      <div className="card mb-6 flex items-center gap-3">
        <Wallet size={24} className="text-red-500" />
        <div><p className="text-sm text-gray-500">إجمالي المصروفات</p><p className="text-xl font-bold text-red-600">{formatCurrency(total)}</p></div>
      </div>

      <div className="card overflow-hidden">
        <table className="w-full">
          <thead><tr className="border-b border-gray-100">
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الوصف</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">المبلغ</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">التاريخ</th>
          </tr></thead>
          <tbody>
            {expenses.map(exp => (
              <tr key={exp.id} className="border-b border-gray-50">
                <td className="py-3 px-4 text-sm">{exp.description}</td>
                <td className="py-3 px-4 text-sm font-medium text-red-600">{formatCurrency(exp.amount)}</td>
                <td className="py-3 px-4 text-sm text-gray-500">{formatDate(exp.date)}</td>
              </tr>
            ))}
            {expenses.length === 0 && <tr><td colSpan={3} className="text-center py-8 text-gray-400">لا توجد مصروفات</td></tr>}
          </tbody>
        </table>
      </div>

      {showAdd && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-sm">
            <h2 className="text-lg font-bold mb-4">مصروف جديد</h2>
            <form onSubmit={handleAdd} className="space-y-4">
              <input type="text" placeholder="الوصف" value={form.description} onChange={e => setForm({...form, description: e.target.value})} className="input-field" required />
              <input type="number" placeholder="المبلغ" value={form.amount || ''} onChange={e => setForm({...form, amount: +e.target.value})} className="input-field" required />
              <input type="date" value={form.date} onChange={e => setForm({...form, date: e.target.value})} className="input-field" />
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
