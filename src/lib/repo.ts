import { db } from './local-db';
import type { Category, Product, Order, OrderItem, OrderItemModifier, Customer, RestaurantTable, DeliveryZone, Modifier, Coupon, Ingredient, Recipe, Employee, Shift, Expense, ExpenseCategory } from './local-db';

function uuid(): string {
  return crypto.randomUUID();
}

// ===== Categories =====
export async function getCategories() {
  return db.categories.orderBy('sort_order').toArray();
}

export async function createCategory(data: { name: string; sort_order?: number }) {
  const id = uuid();
  const record: Category = { id, name: data.name, sort_order: data.sort_order || 0, created_at: new Date().toISOString() };
  await db.categories.put(record);
  return record;
}

export async function updateCategory(id: string, data: Partial<Category>) {
  await db.categories.update(id, data);
}

export async function deleteCategory(id: string) {
  await db.categories.delete(id);
}

// ===== Products =====
export async function getProducts(categoryId?: string) {
  if (categoryId) {
    return db.products.where('category_id').equals(categoryId).toArray();
  }
  return db.products.toArray();
}

export async function getActiveProducts() {
  return db.products.where('is_active').equals(1).toArray();
}

export async function createProduct(data: Omit<Product, 'id' | 'created_at'>) {
  const id = uuid();
  const record = { ...data, id, created_at: new Date().toISOString() };
  await db.products.put(record);
  return record;
}

export async function updateProduct(id: string, data: Partial<Product>) {
  await db.products.update(id, data);
}

export async function deleteProduct(id: string) {
  await db.products.delete(id);
}

// ===== Orders =====
export async function getOrders(filters?: { status?: string; order_type?: string; date?: string }) {
  const results = await db.orders.orderBy('created_at').reverse().toArray();
  return results.filter(order => {
    if (filters?.status && order.status !== filters.status) return false;
    if (filters?.order_type && order.order_type !== filters.order_type) return false;
    if (filters?.date && !order.created_at.startsWith(filters.date)) return false;
    return true;
  });
}

export async function getOrder(id: string) {
  return db.orders.get(id);
}

export async function createOrder(data: Omit<Order, 'id' | 'created_at'>) {
  const id = uuid();
  const record = { ...data, id, created_at: new Date().toISOString() };
  await db.orders.put(record);
  return record;
}

export async function updateOrder(id: string, data: Partial<Order>) {
  await db.orders.update(id, data);
}

export async function deleteOrder(id: string) {
  await db.order_items.where('order_id').equals(id).delete();
  await db.orders.delete(id);
}

// ===== Order Items =====
export async function getOrderItems(orderId: string) {
  return db.order_items.where('order_id').equals(orderId).toArray();
}

export async function createOrderItem(data: Omit<OrderItem, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.order_items.put(record);
  return record;
}

// ===== Order Item Modifiers =====
export async function createOrderItemModifier(data: Omit<OrderItemModifier, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.order_item_modifiers.put(record);
  return record;
}

// ===== Customers =====
export async function getCustomers() {
  return db.customers.toArray();
}

export async function createCustomer(data: { name: string; phone?: string; address?: string; notes?: string }) {
  const id = uuid();
  const record: Customer = { ...data, id, loyalty_points: 0, total_orders: 0, total_spent: 0, created_at: new Date().toISOString() };
  await db.customers.put(record);
  return record;
}

export async function updateCustomer(id: string, data: Partial<Customer>) {
  await db.customers.update(id, data);
}

// ===== Tables =====
export async function getTables() {
  return db.restaurant_tables.toArray();
}

export async function createTable(data: Omit<RestaurantTable, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.restaurant_tables.put(record);
  return record;
}

export async function updateTable(id: string, data: Partial<RestaurantTable>) {
  await db.restaurant_tables.update(id, data);
}

// ===== Delivery Zones =====
export async function getDeliveryZones() {
  return db.delivery_zones.where('is_active').equals(1).toArray();
}

export async function createDeliveryZone(data: Omit<DeliveryZone, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.delivery_zones.put(record);
  return record;
}

// ===== Modifiers =====
export async function getModifiers() {
  return db.modifiers.where('is_active').equals(1).toArray();
}

export async function createModifier(data: Omit<Modifier, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.modifiers.put(record);
  return record;
}

// ===== Coupons =====
export async function getCoupons() {
  return db.coupons.toArray();
}

export async function validateCoupon(code: string) {
  const coupon = await db.coupons.where('code').equals(code).first();
  if (!coupon) return null;
  if (!coupon.is_active) return null;
  if (coupon.expires_at && new Date(coupon.expires_at) < new Date()) return null;
  if (coupon.max_uses > 0 && coupon.current_uses >= coupon.max_uses) return null;
  return coupon;
}

// ===== Ingredients =====
export async function getIngredients() {
  return db.ingredients.toArray();
}

export async function updateIngredient(id: string, data: Partial<Ingredient>) {
  await db.ingredients.update(id, data);
}

export async function createIngredient(data: Omit<Ingredient, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.ingredients.put(record);
  return record;
}

// ===== Employees =====
export async function getEmployees() {
  return db.employees.where('is_active').equals(1).toArray();
}

export async function createEmployee(data: Omit<Employee, 'id' | 'created_at'>) {
  const id = uuid();
  const record = { ...data, id, created_at: new Date().toISOString() };
  await db.employees.put(record);
  return record;
}

// ===== Shifts =====
export async function getShifts() {
  return db.shifts.orderBy('opened_at').reverse().toArray();
}

export async function createShift(data: { opening_cash: number; employee_id?: string; notes?: string }) {
  const id = uuid();
  const record: Shift = { ...data, id, opened_at: new Date().toISOString() };
  await db.shifts.put(record);
  return record;
}

export async function closeShift(id: string, closingCash: number) {
  await db.shifts.update(id, { closing_cash: closingCash, closed_at: new Date().toISOString() });
}

// ===== Expenses =====
export async function getExpenses(date?: string) {
  if (date) {
    return db.expenses.where('date').equals(date).toArray();
  }
  return db.expenses.orderBy('date').reverse().toArray();
}

export async function createExpense(data: Omit<Expense, 'id' | 'created_at'>) {
  const id = uuid();
  const record = { ...data, id, created_at: new Date().toISOString() };
  await db.expenses.put(record);
  return record;
}

export async function getExpenseCategories() {
  return db.expense_categories.toArray();
}
