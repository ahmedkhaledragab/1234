import { useState, useEffect } from 'react';
import { db } from '../lib/local-db';
import { getProducts, getIngredients } from '../lib/repo';
import type { Product, Ingredient, Recipe } from '../lib/local-db';
import { BookOpen } from 'lucide-react';

export function RecipesPage() {
  const [products, setProducts] = useState<Product[]>([]);
  const [ingredients, setIngredients] = useState<Ingredient[]>([]);
  const [recipes, setRecipes] = useState<Recipe[]>([]);

  useEffect(() => { load(); }, []);
  async function load() {
    const [prods, ings, recs] = await Promise.all([getProducts(), getIngredients(), db.recipes.toArray()]);
    setProducts(prods);
    setIngredients(ings);
    setRecipes(recs);
  }

  function getProductName(id: string) { return products.find(p => p.id === id)?.name || '—'; }
  function getIngName(id: string) { return ingredients.find(i => i.id === id)?.name || '—'; }

  // Group recipes by product
  const grouped = recipes.reduce<Record<string, Recipe[]>>((acc, r) => {
    (acc[r.product_id] ||= []).push(r);
    return acc;
  }, {});

  return (
    <div>
      <div className="page-header"><h1 className="page-title">الوصفات</h1></div>
      <div className="space-y-4">
        {Object.entries(grouped).map(([productId, recs]) => (
          <div key={productId} className="card">
            <h3 className="font-bold flex items-center gap-2 mb-3"><BookOpen size={16} className="text-primary-500" />{getProductName(productId)}</h3>
            <div className="space-y-1">
              {recs.map(r => (
                <div key={r.id} className="flex justify-between text-sm py-1 border-b border-gray-50 last:border-0">
                  <span>{getIngName(r.ingredient_id)}</span>
                  <span className="text-gray-500">{r.quantity_needed}</span>
                </div>
              ))}
            </div>
          </div>
        ))}
        {Object.keys(grouped).length === 0 && <div className="text-center py-12 text-gray-400">لا توجد وصفات بعد</div>}
      </div>
    </div>
  );
}
