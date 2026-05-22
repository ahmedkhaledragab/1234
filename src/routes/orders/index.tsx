import { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { getOrders } from '../../lib/repo';
import { formatCurrency, formatDateTime, getStatusLabel, getStatusColor, getOrderTypeLabel } from '../../lib/utils';
import type { Order } from '../../lib/local-db';
import { Search, Filter, Download } from 'lucide-react';

export function OrdersPage() {
  const [orders, setOrders] = useState<Order[]>([]);
  const [statusFilter, setStatusFilter] = useState('');
  const [typeFilter, setTypeFilter] = useState('');
  const [search, setSearch] = useState('');

  useEffect(() => {
    loadOrders();
  }, [statusFilter, typeFilter]);

  async function loadOrders() {
    const data = await getOrders({
      status: statusFilter || undefined,
      order_type: typeFilter || undefined,
    });
    setOrders(data);
  }

  const filteredOrders = search
    ? orders.filter(o => o.order_number.toString().includes(search))
    : orders;

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">الطلبات</h1>
        <button className="btn-secondary flex items-center gap-2">
          <Download size={16} /> تصدير
        </button>
      </div>


      {/* Filters */}
      <div className="flex gap-3 mb-6 flex-wrap">
        <div className="relative flex-1 max-w-xs">
          <Search size={16} className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            placeholder="بحث برقم الطلب..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="input-field pr-10"
          />
        </div>
        <select value={statusFilter} onChange={(e) => setStatusFilter(e.target.value)} className="input-field w-auto">
          <option value="">كل الحالات</option>
          <option value="pending">معلق</option>
          <option value="preparing">قيد التحضير</option>
          <option value="ready">جاهز</option>
          <option value="paid">مدفوع</option>
          <option value="cancelled">ملغي</option>
        </select>
        <select value={typeFilter} onChange={(e) => setTypeFilter(e.target.value)} className="input-field w-auto">
          <option value="">كل الأنواع</option>
          <option value="dine_in">صالة</option>
          <option value="takeaway">تيك أواي</option>
          <option value="delivery">توصيل</option>
          <option value="orders">طلبات</option>
        </select>
      </div>

      {/* Orders Table */}
      <div className="card overflow-hidden">
        <table className="w-full">
          <thead>
            <tr className="border-b border-gray-100">
              <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">#</th>
              <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">النوع</th>
              <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الحالة</th>
              <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الإجمالي</th>
              <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">التاريخ</th>
            </tr>
          </thead>
          <tbody>
            {filteredOrders.map((order) => (
              <tr key={order.id} className="border-b border-gray-50 hover:bg-gray-50">
                <td className="py-3 px-4">
                  <Link to={`/orders/${order.id}`} className="text-primary-600 font-medium hover:underline">
                    #{order.order_number}
                  </Link>
                </td>
                <td className="py-3 px-4 text-sm">{getOrderTypeLabel(order.order_type)}</td>
                <td className="py-3 px-4">
                  <span className={`badge ${getStatusColor(order.status)}`}>{getStatusLabel(order.status)}</span>
                </td>
                <td className="py-3 px-4 font-medium text-sm">{formatCurrency(order.total)}</td>
                <td className="py-3 px-4 text-sm text-gray-500">{formatDateTime(order.created_at)}</td>
              </tr>
            ))}
            {filteredOrders.length === 0 && (
              <tr><td colSpan={5} className="text-center py-8 text-gray-400">لا توجد طلبات</td></tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}
