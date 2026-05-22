import { useState, useEffect } from 'react';
import { getOrders, getExpenses } from '../lib/repo';
import { formatCurrency } from '../lib/utils';
import type { Order } from '../lib/local-db';
import { DollarSign, ShoppingCart, TrendingUp, Wallet } from 'lucide-react';

export function DashboardPage() {
  const [todayOrders, setTodayOrders] = useState<Order[]>([]);
  const [todayRevenue, setTodayRevenue] = useState(0);
  const [todayExpenses, setTodayExpenses] = useState(0);
  const [paidCount, setPaidCount] = useState(0);

  useEffect(() => {
    loadDashboard();
  }, []);

  async function loadDashboard() {
    const today = new Date().toISOString().split('T')[0];
    const orders = await getOrders({ date: today });
    setTodayOrders(orders);
    const paid = orders.filter(o => o.status === 'paid');
    setPaidCount(paid.length);
    setTodayRevenue(paid.reduce((sum, o) => sum + o.total, 0));

    const expenses = await getExpenses(today);
    setTodayExpenses(expenses.reduce((sum, e) => sum + e.amount, 0));
  }

  const stats = [
    { label: 'إيرادات اليوم', value: formatCurrency(todayRevenue), icon: <DollarSign size={24} />, color: 'bg-green-50 text-green-600' },
    { label: 'طلبات اليوم', value: todayOrders.length.toString(), icon: <ShoppingCart size={24} />, color: 'bg-blue-50 text-blue-600' },
    { label: 'طلبات مدفوعة', value: paidCount.toString(), icon: <TrendingUp size={24} />, color: 'bg-purple-50 text-purple-600' },
    { label: 'مصروفات اليوم', value: formatCurrency(todayExpenses), icon: <Wallet size={24} />, color: 'bg-red-50 text-red-600' },
  ];

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">لوحة التحكم</h1>
      </div>

      {/* Stats cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        {stats.map((stat, i) => (
          <div key={i} className="card flex items-center gap-4">
            <div className={`w-12 h-12 rounded-xl flex items-center justify-center ${stat.color}`}>
              {stat.icon}
            </div>
            <div>
              <p className="text-sm text-gray-500">{stat.label}</p>
              <p className="text-xl font-bold">{stat.value}</p>
            </div>
          </div>
        ))}
      </div>

      {/* Recent Orders */}
      <div className="card">
        <h3 className="font-bold mb-4">آخر الطلبات</h3>
        <div className="space-y-3">
          {todayOrders.slice(0, 10).map(order => (
            <div key={order.id} className="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
              <div>
                <span className="font-medium">#{order.order_number}</span>
                <span className="text-sm text-gray-500 mr-3">{order.order_type === 'dine_in' ? 'صالة' : order.order_type === 'delivery' ? 'توصيل' : 'تيك أواي'}</span>
              </div>
              <span className="font-bold text-primary-600">{formatCurrency(order.total)}</span>
            </div>
          ))}
          {todayOrders.length === 0 && <p className="text-center text-gray-400 py-4">لا توجد طلبات اليوم</p>}
        </div>
      </div>
    </div>
  );
}
