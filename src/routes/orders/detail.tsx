import { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { getOrder, getOrderItems, updateOrder } from '../../lib/repo';
import { formatCurrency, formatDateTime, getStatusLabel, getStatusColor, getOrderTypeLabel } from '../../lib/utils';
import type { Order, OrderItem } from '../../lib/local-db';
import { toast } from 'sonner';
import { ArrowRight, Printer, CheckCircle } from 'lucide-react';

const STATUS_FLOW = ['pending', 'preparing', 'ready', 'paid'];
const DELIVERY_STATUS_FLOW = ['pending', 'preparing', 'ready', 'out_for_delivery', 'delivered', 'paid'];

export function OrderDetailPage() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [order, setOrder] = useState<Order | null>(null);
  const [items, setItems] = useState<OrderItem[]>([]);

  useEffect(() => {
    if (id) loadOrder(id);
  }, [id]);

  async function loadOrder(orderId: string) {
    const [o, i] = await Promise.all([getOrder(orderId), getOrderItems(orderId)]);
    setOrder(o || null);
    setItems(i);
  }

  async function advanceStatus() {
    if (!order || !id) return;
    const flow = order.order_type === 'delivery' ? DELIVERY_STATUS_FLOW : STATUS_FLOW;
    const currentIdx = flow.indexOf(order.status);
    if (currentIdx < flow.length - 1) {
      const nextStatus = flow[currentIdx + 1] as Order['status'];
      await updateOrder(id, { status: nextStatus, ...(nextStatus === 'paid' ? { paid_at: new Date().toISOString() } : {}) });
      setOrder({ ...order, status: nextStatus });
      toast.success(`تم تحديث الحالة إلى: ${getStatusLabel(nextStatus)}`);
    }
  }

  if (!order) return <div className="text-center py-12 text-gray-400">جاري التحميل...</div>;

  const flow = order.order_type === 'delivery' ? DELIVERY_STATUS_FLOW : STATUS_FLOW;
  const currentIdx = flow.indexOf(order.status);

  return (
    <div>
      <div className="page-header">
        <div className="flex items-center gap-3">
          <button onClick={() => navigate('/orders')} className="p-2 rounded-lg hover:bg-gray-100">
            <ArrowRight size={20} />
          </button>
          <h1 className="page-title">طلب #{order.order_number}</h1>
          <span className={`badge ${getStatusColor(order.status)}`}>{getStatusLabel(order.status)}</span>
        </div>
        <div className="flex gap-2">
          <button className="btn-secondary flex items-center gap-2"><Printer size={16} /> طباعة</button>
          {order.status !== 'paid' && order.status !== 'cancelled' && (
            <button onClick={advanceStatus} className="btn-primary flex items-center gap-2">
              <CheckCircle size={16} /> {getStatusLabel(flow[currentIdx + 1] || 'paid')}
            </button>
          )}
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Order Info */}
        <div className="card lg:col-span-1">
          <h3 className="font-bold mb-4">معلومات الطلب</h3>
          <dl className="space-y-3 text-sm">
            <div className="flex justify-between"><dt className="text-gray-500">النوع</dt><dd>{getOrderTypeLabel(order.order_type)}</dd></div>
            <div className="flex justify-between"><dt className="text-gray-500">التاريخ</dt><dd>{formatDateTime(order.created_at)}</dd></div>
            {order.notes && <div className="flex justify-between"><dt className="text-gray-500">ملاحظات</dt><dd>{order.notes}</dd></div>}
          </dl>
        </div>

        {/* Items */}
        <div className="card lg:col-span-2">
          <h3 className="font-bold mb-4">الأصناف</h3>
          <table className="w-full">
            <thead><tr className="border-b border-gray-100">
              <th className="text-right py-2 text-sm text-gray-500">الصنف</th>
              <th className="text-right py-2 text-sm text-gray-500">الكمية</th>
              <th className="text-right py-2 text-sm text-gray-500">السعر</th>
              <th className="text-right py-2 text-sm text-gray-500">الإجمالي</th>
            </tr></thead>
            <tbody>
              {items.map((item) => (
                <tr key={item.id} className="border-b border-gray-50">
                  <td className="py-2 text-sm">{item.product_name}{item.variant_name && ` (${item.variant_name})`}</td>
                  <td className="py-2 text-sm">{item.quantity}</td>
                  <td className="py-2 text-sm">{formatCurrency(item.unit_price)}</td>
                  <td className="py-2 text-sm font-medium">{formatCurrency(item.total_price)}</td>
                </tr>
              ))}
            </tbody>
          </table>
          <div className="border-t border-gray-200 mt-4 pt-4 space-y-2">
            <div className="flex justify-between text-sm"><span>المجموع الفرعي</span><span>{formatCurrency(order.subtotal)}</span></div>
            {order.discount_amount > 0 && <div className="flex justify-between text-sm text-green-600"><span>الخصم</span><span>-{formatCurrency(order.discount_amount)}</span></div>}
            <div className="flex justify-between font-bold text-lg"><span>الإجمالي</span><span className="text-primary-600">{formatCurrency(order.total)}</span></div>
          </div>
        </div>
      </div>
    </div>
  );
}
