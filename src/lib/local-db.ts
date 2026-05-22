import Dexie, { type Table } from 'dexie';

// ===== Database Types =====
export interface AppUser {
  id: string;
  name: string;
  email: string;
  password: string; // hashed locally
  role: 'cashier' | 'manager' | 'super_admin';
  is_active: boolean;
  created_at: string;
}

export interface Category {
  id: string;
  name: string;
  sort_order: number;
  created_at: string;
}

export interface Product {
  id: string;
  category_id: string;
  name: string;
  description?: string;
  image_url?: string;
  price_dine_in: number;
  price_takeaway: number;
  price_delivery: number;
  price_orders: number;
  is_active: boolean;
  sort_order: number;
  created_at: string;
}

export interface ProductVariant {
  id: string;
  product_id: string;
  name: string;
  price_dine_in: number;
  price_takeaway: number;
  price_delivery: number;
  price_orders: number;
}

export interface Modifier {
  id: string;
  name: string;
  price: number;
  category?: string;
  is_active: boolean;
}

export interface Customer {
  id: string;
  name: string;
  phone?: string;
  address?: string;
  notes?: string;
  loyalty_points: number;
  total_orders: number;
  total_spent: number;
  created_at: string;
}

export interface Order {
  id: string;
  order_number: number;
  order_type: 'dine_in' | 'takeaway' | 'delivery' | 'orders';
  status: 'pending' | 'preparing' | 'ready' | 'out_for_delivery' | 'delivered' | 'paid' | 'cancelled';
  table_id?: string;
  customer_id?: string;
  delivery_zone_id?: string;
  delivery_address?: string;
  subtotal: number;
  discount_amount: number;
  discount_type?: 'percentage' | 'fixed';
  tax_amount: number;
  delivery_fee: number;
  total: number;
  payment_method?: 'cash' | 'card' | 'wallet';
  notes?: string;
  coupon_code?: string;
  created_by?: string;
  created_at: string;
  paid_at?: string;
}

export interface OrderItem {
  id: string;
  order_id: string;
  product_id: string;
  variant_id?: string;
  product_name: string;
  variant_name?: string;
  quantity: number;
  unit_price: number;
  total_price: number;
  notes?: string;
}

export interface OrderItemModifier {
  id: string;
  order_item_id: string;
  modifier_id: string;
  modifier_name: string;
  modifier_price: number;
}

export interface RestaurantTable {
  id: string;
  name: string;
  capacity: number;
  status: 'available' | 'occupied' | 'reserved';
  current_order_id?: string;
  zone?: string;
}

export interface DeliveryZone {
  id: string;
  name: string;
  delivery_fee: number;
  estimated_time?: string;
  is_active: boolean;
}

export interface Coupon {
  id: string;
  code: string;
  discount_type: 'percentage' | 'fixed';
  discount_value: number;
  min_order_amount: number;
  max_uses: number;
  current_uses: number;
  expires_at?: string;
  is_active: boolean;
}

export interface Ingredient {
  id: string;
  name: string;
  unit: string;
  quantity: number;
  min_quantity: number;
  cost_per_unit: number;
}

export interface Recipe {
  id: string;
  product_id: string;
  ingredient_id: string;
  quantity_needed: number;
}

export interface Employee {
  id: string;
  name: string;
  phone?: string;
  role: string;
  salary: number;
  is_active: boolean;
  created_at: string;
}

export interface Shift {
  id: string;
  employee_id?: string;
  opening_cash: number;
  closing_cash?: number;
  opened_at: string;
  closed_at?: string;
  notes?: string;
}

export interface Expense {
  id: string;
  category_id?: string;
  description: string;
  amount: number;
  date: string;
  created_at: string;
}

export interface ExpenseCategory {
  id: string;
  name: string;
}

// ===== Dexie Database =====
class CityPOSDatabase extends Dexie {
  app_users!: Table<AppUser, string>;
  categories!: Table<Category, string>;
  products!: Table<Product, string>;
  product_variants!: Table<ProductVariant, string>;
  modifiers!: Table<Modifier, string>;
  customers!: Table<Customer, string>;
  orders!: Table<Order, string>;
  order_items!: Table<OrderItem, string>;
  order_item_modifiers!: Table<OrderItemModifier, string>;
  restaurant_tables!: Table<RestaurantTable, string>;
  delivery_zones!: Table<DeliveryZone, string>;
  coupons!: Table<Coupon, string>;
  ingredients!: Table<Ingredient, string>;
  recipes!: Table<Recipe, string>;
  employees!: Table<Employee, string>;
  shifts!: Table<Shift, string>;
  expenses!: Table<Expense, string>;
  expense_categories!: Table<ExpenseCategory, string>;

  constructor() {
    super('CityPOSDB');
    this.version(1).stores({
      app_users: 'id, email, role, is_active',
      categories: 'id, sort_order',
      products: 'id, category_id, is_active',
      product_variants: 'id, product_id',
      modifiers: 'id, is_active',
      customers: 'id, phone, name',
      orders: 'id, order_number, status, order_type, created_at, customer_id, table_id',
      order_items: 'id, order_id, product_id',
      order_item_modifiers: 'id, order_item_id',
      restaurant_tables: 'id, status',
      delivery_zones: 'id, is_active',
      coupons: 'id, code, is_active',
      ingredients: 'id, name',
      recipes: 'id, product_id, ingredient_id',
      employees: 'id, is_active',
      shifts: 'id, opened_at',
      expenses: 'id, date, category_id',
      expense_categories: 'id',
    });
  }
}

export const db = new CityPOSDatabase();

// Seed default admin user on first run
export async function seedDefaultUser() {
  const count = await db.app_users.count();
  if (count === 0) {
    await db.app_users.add({
      id: crypto.randomUUID(),
      name: 'مدير النظام',
      email: 'admin',
      password: 'admin', // plain text for local desktop app
      role: 'super_admin',
      is_active: true,
      created_at: new Date().toISOString(),
    });
  }
}
