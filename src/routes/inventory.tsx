import { useState, useEffect } from 'react';
import { getIngredients, updateIngredient } from '../lib/repo';
import type { Ingredient } from '../lib/local-db';
import { toast } from 'sonner';
import { AlertTriangle, Package } from 'lucide-react';

export function InventoryPage() {
  const [ingredients, setIngredients] = useState<Ingredient[]>([]);

  useEffect(() => { load(); }, []);
  async function load() { setIngredients(await getIngredients()); }

  async function adjustStock(id: string, amount: number) {
    const ing = ingredients.find(i => i.id === id);
    if (!ing) return;
    await updateIngredient(id, { quantity: ing.quantity + amount });
    toast.success('تم تحديث المخزون');
    load();
  }

  return (
    <div>
      <div className="page-header"><h1 className="page-title">المخزون</h1></div>
      <div className="card overflow-hidden">
        <table className="w-full">
          <thead><tr className="border-b border-gray-100">
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">المادة</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الكمية</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الوحدة</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">الحد الأدنى</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">حالة</th>
            <th className="text-right py-3 px-4 text-sm font-medium text-gray-500">إجراء</th>
          </tr></thead>
          <tbody>
            {ingredients.map(ing => (
              <tr key={ing.id} className="border-b border-gray-50">
                <td className="py-3 px-4 font-medium text-sm">{ing.name}</td>
                <td className="py-3 px-4 text-sm">{ing.quantity}</td>
                <td className="py-3 px-4 text-sm text-gray-500">{ing.unit}</td>
                <td className="py-3 px-4 text-sm text-gray-500">{ing.min_quantity}</td>
                <td className="py-3 px-4">
                  {ing.quantity <= ing.min_quantity ? (
                    <span className="badge bg-red-100 text-red-700 flex items-center gap-1 w-fit"><AlertTriangle size={12} /> منخفض</span>
                  ) : (
                    <span className="badge bg-green-100 text-green-700">متوفر</span>
                  )}
                </td>
                <td className="py-3 px-4">
                  <div className="flex gap-1">
                    <button onClick={() => adjustStock(ing.id, 10)} className="text-xs btn-primary px-2 py-1">+10</button>
                    <button onClick={() => adjustStock(ing.id, -1)} className="text-xs btn-danger px-2 py-1">-1</button>
                  </div>
                </td>
              </tr>
            ))}
            {ingredients.length === 0 && <tr><td colSpan={6} className="text-center py-8 text-gray-400">لا توجد مواد خام</td></tr>}
          </tbody>
        </table>
      </div>
    </div>
  );
}
