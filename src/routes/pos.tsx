import { useState, useEffect, useMemo } from 'react';
import { useCartStore, type OrderType } from '../lib/cart-store';
import { getCategories, getActiveProducts, createOrder, createOrderItem } from '../lib/repo';
import { formatCurrency, getOrderTypeLabel } from '../lib/utils';
import { printReceipt, printKitchenTicket } from '../lib/qz-printer';
import { getReceiptSettings } from '../lib/receipt-settings';
import type { Category, Product } from '../lib/local-db';
import { toast } from 'sonner';
import {
  ShoppingCart, Minus, Plus, Trash2, Store, Coffee, Truck, ClipboardList
} from 'lucide-react';

const ORDER_TYPES: { value: OrderType; label: string; icon: React.ReactNode }[] = [
  { value: 'dine_in', label: 'صالة', icon: <Store size={16} /> },
  { value: 'takeaway', label: 'تيك أواي', icon: <Coffee size={16} /> },
  { value: 'delivery', label: 'توصيل', icon: <Truck size={16} /> },
  { value: 'orders', label: 'طلبات', icon: <ClipboardList size={16} /> },
];

export function POSPage() {
  const [categories, setCategories] = useState<Category[]>([]);
  const [products, setProducts] = useState<Product[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null);
  const [searchQuery, setSearchQuery] = useState('');

  const cart = useCartStore();


  useEffect(() => {
    loadData();
  }, []);

  async function loadData() {
    const [cats, prods] = await Promise.all([getCategories(), getActiveProducts()]);
    setCategories(cats);
    setProducts(prods);
  }

  const filteredProducts = useMemo(() => {
    let filtered = products;
    if (selectedCategory) {
      filtered = filtered.filter(p => p.category_id === selectedCategory);
    }
    if (searchQuery) {
      filtered = filtered.filter(p => p.name.includes(searchQuery));
    }
    return filtered;
  }, [products, selectedCategory, searchQuery]);

  function getPrice(product: Product): number {
    switch (cart.orderType) {
      case 'dine_in': return product.price_dine_in;
      case 'takeaway': return product.price_takeaway;
      case 'delivery': return product.price_delivery;
      case 'orders': return product.price_orders;
      default: return product.price_dine_in;
    }
  }

  function addToCart(product: Product) {
    cart.addItem({
      product_id: product.id,
      name: product.name,
      quantity: 1,
      unit_price: getPrice(product),
      modifiers: [],
    });
  }

  async function submitOrder() {
    if (cart.items.length === 0) {
      toast.error('السلة فارغة');
      return;
    }
    try {
      const subtotal = cart.getSubtotal();
      const total = cart.getTotal();
      const order = await createOrder({
        order_number: Date.now() % 10000,
        order_type: cart.orderType,
        status: 'pending',
        table_id: cart.tableId || undefined,
        customer_id: cart.customerId || undefined,
        delivery_zone_id: cart.deliveryZoneId || undefined,
        delivery_address: cart.deliveryAddress || undefined,
        subtotal,
        discount_amount: cart.discountAmount,
        discount_type: cart.discountType || undefined,
        tax_amount: 0,
        delivery_fee: 0,
        total,
        notes: cart.notes || undefined,
        coupon_code: cart.couponCode || undefined,
      });

      // Create order items
      for (const item of cart.items) {
        await createOrderItem({
          order_id: order.id,
          product_id: item.product_id,
          variant_id: item.variant_id,
          product_name: item.name,
          variant_name: item.variant_name,
          quantity: item.quantity,
          unit_price: item.unit_price,
          total_price: item.unit_price * item.quantity,
          notes: item.notes,
        });
      }

      cart.clearCart();
      toast.success(`تم إنشاء الطلب #${order.order_number}`);

      // Auto-print receipt & kitchen ticket
      const settings = getReceiptSettings();
      if (settings.autoPrint && settings.printerName) {
        const printData = {
          orderNumber: order.order_number,
          orderType: cart.orderType,
          items: cart.items.map(item => ({
            name: item.name,
            quantity: item.quantity,
            price: item.unit_price,
            modifiers: item.modifiers.map(m => m.name),
            notes: item.notes,
          })),
          subtotal,
          discount: cart.discountAmount,
          total,
          notes: cart.notes || undefined,
        };

        // Print customer receipt
        printReceipt(printData).catch(console.error);

        // Print kitchen ticket
        printKitchenTicket({
          orderNumber: order.order_number,
          orderType: cart.orderType,
          items: cart.items.map(item => ({
            name: item.name,
            quantity: item.quantity,
            modifiers: item.modifiers.map(m => m.name),
            notes: item.notes,
          })),
          notes: cart.notes || undefined,
        }).catch(console.error);
      }
    } catch (err) {
      toast.error('حدث خطأ أثناء إنشاء الطلب');
    }
  }


  return (
    <div className="flex h-[calc(100vh-3rem)] gap-4 -m-6">
      {/* Products Panel */}
      <div className="flex-1 flex flex-col p-4 overflow-hidden">
        {/* Order Type Tabs */}
        <div className="flex gap-2 mb-4">
          {ORDER_TYPES.map((type) => (
            <button
              key={type.value}
              onClick={() => cart.setOrderType(type.value)}
              className={`flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-colors ${
                cart.orderType === type.value
                  ? 'bg-primary-600 text-white'
                  : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200'
              }`}
            >
              {type.icon}
              {type.label}
            </button>
          ))}
        </div>

        {/* Search */}
        <input
          type="text"
          placeholder="بحث عن منتج..."
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          className="input-field mb-4"
        />

        {/* Categories */}
        <div className="flex gap-2 mb-4 overflow-x-auto pb-2">
          <button
            onClick={() => setSelectedCategory(null)}
            className={`shrink-0 px-3 py-1.5 rounded-full text-sm font-medium transition-colors ${
              !selectedCategory ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
            }`}
          >
            الكل
          </button>
          {categories.map((cat) => (
            <button
              key={cat.id}
              onClick={() => setSelectedCategory(cat.id)}
              className={`shrink-0 px-3 py-1.5 rounded-full text-sm font-medium transition-colors ${
                selectedCategory === cat.id ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
              }`}
            >
              {cat.name}
            </button>
          ))}
        </div>

        {/* Products Grid */}
        <div className="flex-1 overflow-y-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 content-start">
          {filteredProducts.map((product) => (
            <button
              key={product.id}
              onClick={() => addToCart(product)}
              className="bg-white border border-gray-200 rounded-xl p-3 text-center hover:border-primary-300 hover:shadow-sm transition-all"
            >
              {product.image_url && (
                <img src={product.image_url} alt={product.name} className="w-full h-20 object-cover rounded-lg mb-2" />
              )}
              <p className="font-medium text-sm text-gray-800 mb-1">{product.name}</p>
              <p className="text-primary-600 font-bold text-sm">{formatCurrency(getPrice(product))}</p>
            </button>
          ))}
        </div>
      </div>


      {/* Cart Panel */}
      <div className="w-80 bg-white border-r border-gray-200 flex flex-col p-4">
        <div className="flex items-center gap-2 mb-4">
          <ShoppingCart size={20} className="text-primary-600" />
          <h2 className="font-bold text-lg">السلة</h2>
          <span className="badge bg-primary-100 text-primary-700 mr-auto">{cart.items.length}</span>
        </div>

        {/* Cart Items */}
        <div className="flex-1 overflow-y-auto space-y-3">
          {cart.items.length === 0 ? (
            <div className="text-center text-gray-400 py-8">
              <ShoppingCart size={48} className="mx-auto mb-2 opacity-50" />
              <p>السلة فارغة</p>
            </div>
          ) : (
            cart.items.map((item) => (
              <div key={item.id} className="bg-gray-50 rounded-lg p-3">
                <div className="flex items-start justify-between mb-2">
                  <p className="font-medium text-sm">{item.name}</p>
                  <button onClick={() => cart.removeItem(item.id)} className="text-red-400 hover:text-red-600">
                    <Trash2 size={14} />
                  </button>
                </div>
                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-2">
                    <button
                      onClick={() => cart.updateQuantity(item.id, item.quantity - 1)}
                      className="w-6 h-6 rounded bg-gray-200 flex items-center justify-center hover:bg-gray-300"
                    >
                      <Minus size={12} />
                    </button>
                    <span className="text-sm font-medium w-6 text-center">{item.quantity}</span>
                    <button
                      onClick={() => cart.updateQuantity(item.id, item.quantity + 1)}
                      className="w-6 h-6 rounded bg-gray-200 flex items-center justify-center hover:bg-gray-300"
                    >
                      <Plus size={12} />
                    </button>
                  </div>
                  <p className="text-sm font-bold text-primary-600">
                    {formatCurrency(item.unit_price * item.quantity)}
                  </p>
                </div>
              </div>
            ))
          )}
        </div>

        {/* Cart Footer */}
        {cart.items.length > 0 && (
          <div className="border-t border-gray-100 pt-4 mt-4 space-y-3">
            <div className="flex justify-between text-sm">
              <span className="text-gray-500">المجموع الفرعي</span>
              <span className="font-medium">{formatCurrency(cart.getSubtotal())}</span>
            </div>
            {cart.discountAmount > 0 && (
              <div className="flex justify-between text-sm text-green-600">
                <span>الخصم</span>
                <span>-{cart.discountType === 'percentage' ? `${cart.discountAmount}%` : formatCurrency(cart.discountAmount)}</span>
              </div>
            )}
            <div className="flex justify-between text-lg font-bold">
              <span>الإجمالي</span>
              <span className="text-primary-600">{formatCurrency(cart.getTotal())}</span>
            </div>
            <button onClick={submitOrder} className="btn-primary w-full py-3 text-base">
              تأكيد الطلب
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
