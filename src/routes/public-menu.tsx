import { useState, useEffect } from 'react';
import { getCategories, getActiveProducts } from '../lib/repo';
import { formatCurrency } from '../lib/utils';
import type { Category, Product } from '../lib/local-db';
import { Store } from 'lucide-react';

export function PublicMenuPage() {
  const [categories, setCategories] = useState<Category[]>([]);
  const [products, setProducts] = useState<Product[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<string | null>(null);

  useEffect(() => {
    async function load() {
      const [cats, prods] = await Promise.all([getCategories(), getActiveProducts()]);
      setCategories(cats);
      setProducts(prods);
    }
    load();
  }, []);

  const filtered = selectedCategory ? products.filter(p => p.category_id === selectedCategory) : products;

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow-sm border-b">
        <div className="max-w-4xl mx-auto px-4 py-6 text-center">
          <div className="flex items-center justify-center gap-2 mb-2">
            <Store size={28} className="text-primary-600" />
            <h1 className="text-2xl font-bold text-primary-600">ستي</h1>
          </div>
          <p className="text-gray-500">قائمة الطعام</p>
        </div>
      </div>

      <div className="max-w-4xl mx-auto px-4 py-6">
        {/* Categories */}
        <div className="flex gap-2 mb-6 overflow-x-auto pb-2">
          <button onClick={() => setSelectedCategory(null)} className={`shrink-0 px-4 py-2 rounded-full text-sm font-medium ${!selectedCategory ? 'bg-primary-600 text-white' : 'bg-white border'}`}>الكل</button>
          {categories.map(cat => (
            <button key={cat.id} onClick={() => setSelectedCategory(cat.id)} className={`shrink-0 px-4 py-2 rounded-full text-sm font-medium ${selectedCategory === cat.id ? 'bg-primary-600 text-white' : 'bg-white border'}`}>{cat.name}</button>
          ))}
        </div>

        {/* Products */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {filtered.map(product => (
            <div key={product.id} className="bg-white rounded-xl p-4 shadow-sm flex gap-4">
              {product.image_url && <img src={product.image_url} alt={product.name} className="w-20 h-20 object-cover rounded-lg" />}
              <div className="flex-1">
                <h3 className="font-bold text-sm">{product.name}</h3>
                {product.description && <p className="text-xs text-gray-500 mt-1">{product.description}</p>}
                <p className="text-primary-600 font-bold mt-2">{formatCurrency(product.price_dine_in)}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
