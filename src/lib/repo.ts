import { db } from './local-db';
import { addToOutbox } from './sync';

// Generate UUID
function uuid(): string {
  return crypto.randomUUID();
}

// ===== Categories =====
export async function getCategories() {
  return db.categories.orderBy('sort_order').toArray();
}

export async function createCategory(data: { name: string; sort_order?: number }) {
  const id = uuid();
  const record = { id, name: data.name, sort_order: data.sort_order || 0, created_at: new Date().toISOString() };
  await db.categories.put(record);
  await addToOutbox('categories', 'insert', id, record);
  return record;
}

export async function updateCategory(id: string, data: Partial<{ name: string; sort_order: number }>) {
  await db.categories.update(id, data);
  await addToOutbox('categories', 'update', id, data);
}

export async function deleteCategory(id: string) {
  await db.categories.delete(id);
  await addToOutbox('categories', 'delete', id, {});
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

export async function createProduct(data: Omit<import('./local-db').Product, 'id' | 'created_at'>) {
  const id = uuid();
  const record = { ...data, id, created_at: new Date().toISOString() };
  await db.products.put(record);
  await addToOutbox('products', 'insert', id, record);
  return record;
}

export async function updateProduct(id: string, data: Partial<import('./local-db').Product>) {
  await db.products.update(id, data);
  await addToOutbox('products', 'update', id, data);
}

export async function deleteProduct(id: string) {
  await db.products.delete(id);
  await addToOutbox('products', 'delete', id, {});
}

// ===== Orders =====
export async function getOrders(filters?: { status?: string; order_type?: string; date?: string }) {
  let collection = db.orders.orderBy('created_at').reverse();
  const results = await collection.toArray();
  
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

export async function createOrder(data: Omit<import('./local-db').Order, 'id' | 'created_at'>) {
  const id = uuid();
  const record = { ...data, id, created_at: new Date().toISOString() };
  await db.orders.put(record);
  await addToOutbox('orders', 'insert', id, record);
  return record;
}

export async function updateOrder(id: string, data: Partial<import('./local-db').Order>) {
  await db.orders.update(id, data);
  await addToOutbox('orders', 'update', id, data);
}

// ===== Order Items =====
export async function getOrderItems(orderId: string) {
  return db.order_items.where('order_id').equals(orderId).toArray();
}

export async function createOrderItem(data: Omit<import('./local-db').OrderItem, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.order_items.put(record);
  await addToOutbox('order_items', 'insert', id, record);
  return record;
}

// ===== Order Item Modifiers =====
export async function createOrderItemModifier(data: Omit<import('./local-db').OrderItemModifier, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.order_item_modifiers.put(record);
  await addToOutbox('order_item_modifiers', 'insert', id, record);
  return record;
}

// ===== Customers =====
export async function getCustomers() {
  return db.customers.toArray();
}

export async function createCustomer(data: { name: string; phone?: string; address?: string; notes?: string }) {
  const id = uuid();
  const record = { ...data, id, loyalty_points: 0, total_orders: 0, total_spent: 0, created_at: new Date().toISOString() };
  await db.customers.put(record);
  await addToOutbox('customers', 'insert', id, record);
  return record;
}

export async function updateCustomer(id: string, data: Partial<import('./local-db').Customer>) {
  await db.customers.update(id, data);
  await addToOutbox('customers', 'update', id, data);
}

// ===== Tables =====
export async function getTables() {
  return db.restaurant_tables.toArray();
}

export async function updateTable(id: string, data: Partial<import('./local-db').RestaurantTable>) {
  await db.restaurant_tables.update(id, data);
  await addToOutbox('restaurant_tables', 'update', id, data);
}

export async function createTable(data: Omit<import('./local-db').RestaurantTable, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.restaurant_tables.put(record);
  await addToOutbox('restaurant_tables', 'insert', id, record);
  return record;
}

// ===== Delivery Zones =====
export async function getDeliveryZones() {
  return db.delivery_zones.where('is_active').equals(1).toArray();
}

export async function createDeliveryZone(data: Omit<import('./local-db').DeliveryZone, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.delivery_zones.put(record);
  await addToOutbox('delivery_zones', 'insert', id, record);
  return record;
}

// ===== Modifiers =====
export async function getModifiers() {
  return db.modifiers.where('is_active').equals(1).toArray();
}

export async function createModifier(data: Omit<import('./local-db').Modifier, 'id'>) {
  const id = uuid();
  const record = { ...data, id };
  await db.modifiers.put(record);
  await addToOutbox('modifiers', 'insert', id, record);
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

export async function updateIngredient(id: string, data: Partial<import('./local-db').Ingredient>) {
  await db.ingredients.update(id, data);
  await addToOutbox('ingredients', 'update', id, data);
}

// ===== Employees =====
export async function getEmployees() {
  return db.employees.where('is_active').equals(1).toArray();
}

export async function createEmployee(data: Omit<import('./local-db').Employee, 'id' | 'created_at'>) {
  const id = uuid();
  const record = { ...data, id, created_at: new Date().toISOString() };
  await db.employees.put(record);
  await addToOutbox('employees', 'insert', id, record);
  return record;
}

// ===== Shifts =====
export async function getShifts() {
  return db.shifts.orderBy('opened_at').reverse().toArray();
}

export async function createShift(data: { opening_cash: number; employee_id?: string; notes?: string }) {
  const id = uuid();
  const record = { ...data, id, opened_at: new Date().toISOString() };
  await db.shifts.put(record);
  await addToOutbox('shifts', 'insert', id, record);
  return record;
}

export async function closeShift(id: string, closingCash: number) {
  const data = { closing_cash: closingCash, closed_at: new Date().toISOString() };
  await db.shifts.update(id, data);
  await addToOutbox('shifts', 'update', id, data);
}

// ===== Expenses =====
export async function getExpenses(date?: string) {
  if (date) {
    return (await db.expenses.where('date').equals(date).toArray());
  }
  return db.expenses.orderBy('date').reverse().toArray();
}

export async function createExpense(data: Omit<import('./local-db').Expense, 'id' | 'created_at'>) {
  const id = uuid();
  const record = { ...data, id, created_at: new Date().toISOString() };
  await db.expenses.put(record);
  await addToOutbox('expenses', 'insert', id, record);
  return record;
}

export async function getExpenseCategories() {
  return db.expense_categories.toArray();
}
