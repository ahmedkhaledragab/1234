import { useState, useEffect } from 'react';
import { getCategories, getProducts, createCategory, createProduct, updateProduct, deleteProduct } from '../lib/repo';
import { formatCurrency } from '../lib/utils';
import type { Category, Product } from '../lib/local-db';
import { toast } from 'sonner';
import { Plus, Edit, Trash2, Package } from 'lucide-react';

export function MenuPage() {
  const [categories, setCategories] = useState<Category[]>([]);
  const [products, setProducts] = useState<Product[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null);
  const [showAddProduct, setShowAddProduct] = useState(false);
  const [newProduct, setNewProduct] = useState({
    name: '', category_id: '', price_dine_in: 0, price_takeaway: 0, price_delivery: 0, price_orders: 0
  });

  useEffect(() => { loadData(); }, []);

  async function loadData() {
    const [cats, prods] = await Promise.all([getCategories(), getProducts()]);
    setCategories(cats);
    setProducts(prods);
  }

  async function handleAddCategory() {
    const name = prompt('اسم الفئة الجديدة:');
    if (name) {
      await createCategory({ name });
      toast.success('تم إضافة الفئة');
      loadData();
    }
  }

  async function handleAddProduct(e: React.FormEvent) {
    e.preventDefault();
    await createProduct({
      ...newProduct,
      is_active: true,
      sort_order: 0,
    });
    toast.success('تم إضافة المنتج');
    setShowAddProduct(false);
    setNewProduct({ name: '', category_id: '', price_dine_in: 0, price_takeaway: 0, price_delivery: 0, price_orders: 0 });
    loadData();
  }

  async function handleDelete(id: string) {
    if (confirm('هل أنت متأكد من الحذف؟')) {
      await deleteProduct(id);
      toast.success('تم حذف المنتج');
      loadData();
    }
  }

  const filtered = selectedCategory ? products.filter(p => p.category_id === selectedCategory) : products;

  return (
    <div>
      <div className="page-header">
        <h1 className="page-title">إدارة المنيو</h1>
        <div className="flex gap-2">
          <button onClick={handleAddCategory} className="btn-secondary flex items-center gap-2"><Plus size={16} /> فئة جديدة</button>
          <button onClick={() => setShowAddProduct(true)} className="btn-primary flex items-center gap-2"><Plus size={16} /> منتج جديد</button>
        </div>
      </div>

      {/* Categories tabs */}
      <div className="flex gap-2 mb-6 overflow-x-auto pb-2">
        <button onClick={() => setSelectedCategory(null)} className={`shrink-0 px-4 py-2 rounded-lg text-sm font-medium ${!selectedCategory ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200'}`}>الكل ({products.length})</button>
        {categories.map(cat => (
          <button key={cat.id} onClick={() => setSelectedCategory(cat.id)} className={`shrink-0 px-4 py-2 rounded-lg text-sm font-medium ${selectedCategory === cat.id ? 'bg-primary-600 text-white' : 'bg-white border border-gray-200'}`}>
            {cat.name} ({products.filter(p => p.category_id === cat.id).length})
          </button>
        ))}
      </div>

      {/* Products grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {filtered.map(product => (
          <div key={product.id} className="card flex items-start gap-3">
            <div className="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
              {product.image_url ? <img src={product.image_url} className="w-full h-full object-cover rounded-lg" /> : <Package size={24} className="text-gray-400" />}
            </div>
            <div className="flex-1 min-w-0">
              <h3 className="font-medium text-sm mb-1">{product.name}</h3>
              <p className="text-xs text-gray-500">صالة: {formatCurrency(product.price_dine_in)} | تيك أواي: {formatCurrency(product.price_takeaway)}</p>
            </div>
            <div className="flex gap-1">
              <button onClick={() => handleDelete(product.id)} className="p-1.5 rounded hover:bg-red-50 text-red-500"><Trash2 size={14} /></button>
            </div>
          </div>
        ))}
      </div>

      {/* Add Product Modal */}
      {showAddProduct && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
          <div className="bg-white rounded-2xl p-6 w-full max-w-md">
            <h2 className="text-lg font-bold mb-4">إضافة منتج جديد</h2>
            <form onSubmit={handleAddProduct} className="space-y-4">
              <input type="text" placeholder="اسم المنتج" value={newProduct.name} onChange={(e) => setNewProduct({...newProduct, name: e.target.value})} className="input-field" required />
              <select value={newProduct.category_id} onChange={(e) => setNewProduct({...newProduct, category_id: e.target.value})} className="input-field" required>
                <option value="">اختر الفئة</option>
                {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
              </select>
              <div className="grid grid-cols-2 gap-3">
                <input type="number" placeholder="سعر الصالة" value={newProduct.price_dine_in || ''} onChange={(e) => setNewProduct({...newProduct, price_dine_in: +e.target.value})} className="input-field" />
                <input type="number" placeholder="سعر تيك أواي" value={newProduct.price_takeaway || ''} onChange={(e) => setNewProduct({...newProduct, price_takeaway: +e.target.value})} className="input-field" />
                <input type="number" placeholder="سعر التوصيل" value={newProduct.price_delivery || ''} onChange={(e) => setNewProduct({...newProduct, price_delivery: +e.target.value})} className="input-field" />
                <input type="number" placeholder="سعر الطلبات" value={newProduct.price_orders || ''} onChange={(e) => setNewProduct({...newProduct, price_orders: +e.target.value})} className="input-field" />
              </div>
              <div className="flex gap-3">
                <button type="submit" className="btn-primary flex-1">إضافة</button>
                <button type="button" onClick={() => setShowAddProduct(false)} className="btn-secondary flex-1">إلغاء</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
