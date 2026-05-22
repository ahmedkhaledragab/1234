import { useState, useEffect } from 'react';
import { getOrders, getExpenses } from '../lib/repo';
import { formatCurrency } from '../lib/utils';
import type { Order } from '../lib/local-db';
import { BarChart3, Download } from 'lucide-react';

export function ReportsPage() {
  const [dateFrom, setDateFrom] = useState(new Date().toISOString().split('T')[0]);
  const [dateTo, setDateTo] = useState(new Date().toISOString().split('T')[0]);
  const [orders, setOrders] = useState<Order[]>([]);
  const [totalRevenue, setTotalRevenue] = useState(0);
  const [totalExpenses, setTotalExpenses] = useState(0);

  useEffect(() => { loadReport(); }, [dateFrom, dateTo]);

  async function loadReport() {
    const allOrders = await getOrders();
    const filtered = allOrders.filter(o => {
      const d = o.created_at.split('T')[0];
      return d >= dateFrom && d <= dateTo && o.status === 'paid';
    });
    setOrders(filtered);
    setTotalRevenue(filtered.reduce((s, o) => s + o.total, 0));

    const allExp = await getExpenses();
    const filteredExp = allExp.filter(e => e.date >= dateFrom && e.date <= dateTo);
    setTotalExpenses(filteredExp.reduce((s, e) => s + e.amount, 0));
  }

  const netProfit = totalRevenue - totalExpenses;

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">التقارير</h1>
        <button className="btn-secondary flex items-center gap-2"><Download size={16} /> تصدير CSV</button>
      </div>

      {/* Date filter */}
      <div className="flex gap-4 mb-6 items-end">
        <div><label className="text-sm text-gray-500 block mb-1">من</label><input type="date" value={dateFrom} onChange={e => setDateFrom(e.target.value)} className="input-field" /></div>
        <div><label className="text-sm text-gray-500 block mb-1">إلى</label><input type="date" value={dateTo} onChange={e => setDateTo(e.target.value)} className="input-field" /></div>
      </div>

      {/* Summary */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div className="card text-center"><p className="text-sm text-gray-500">الإيرادات</p><p className="text-2xl font-bold text-green-600">{formatCurrency(totalRevenue)}</p></div>
        <div className="card text-center"><p className="text-sm text-gray-500">المصروفات</p><p className="text-2xl font-bold text-red-600">{formatCurrency(totalExpenses)}</p></div>
        <div className="card text-center"><p className="text-sm text-gray-500">صافي الربح</p><p className={`text-2xl font-bold ${netProfit >= 0 ? 'text-green-600' : 'text-red-600'}`}>{formatCurrency(netProfit)}</p></div>
      </div>

      {/* Orders breakdown */}
      <div className="card">
        <h3 className="font-bold mb-4 flex items-center gap-2"><BarChart3 size={18} /> تفاصيل ({orders.length} طلب مدفوع)</h3>
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
          {(['dine_in', 'takeaway', 'delivery', 'orders'] as const).map(type => {
            const typeOrders = orders.filter(o => o.order_type === type);
            const labels: Record<string, string> = { dine_in: 'صالة', takeaway: 'تيك أواي', delivery: 'توصيل', orders: 'طلبات' };
            return (
              <div key={type} className="bg-gray-50 rounded-lg p-3 text-center">
                <p className="text-xs text-gray-500">{labels[type]}</p>
                <p className="font-bold">{typeOrders.length} طلب</p>
                <p className="text-sm text-primary-600">{formatCurrency(typeOrders.reduce((s, o) => s + o.total, 0))}</p>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}
