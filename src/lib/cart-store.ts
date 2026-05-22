import { create } from 'zustand';

export type OrderType = 'dine_in' | 'takeaway' | 'delivery' | 'orders';

export interface CartModifier {
  id: string;
  name: string;
  price: number;
}

export interface CartItem {
  id: string;
  product_id: string;
  variant_id?: string;
  name: string;
  variant_name?: string;
  quantity: number;
  unit_price: number;
  modifiers: CartModifier[];
  notes?: string;
}

interface CartState {
  items: CartItem[];
  orderType: OrderType;
  tableId: string | null;
  customerId: string | null;
  deliveryZoneId: string | null;
  deliveryAddress: string;
  couponCode: string | null;
  discountAmount: number;
  discountType: 'percentage' | 'fixed' | null;
  notes: string;

  addItem: (item: Omit<CartItem, 'id'>) => void;
  removeItem: (id: string) => void;
  updateQuantity: (id: string, quantity: number) => void;
  setOrderType: (type: OrderType) => void;
  setTableId: (id: string | null) => void;
  setCustomerId: (id: string | null) => void;
  setDeliveryZoneId: (id: string | null) => void;
  setDeliveryAddress: (address: string) => void;
  setCoupon: (code: string | null) => void;
  setDiscount: (amount: number, type: 'percentage' | 'fixed' | null) => void;
  setNotes: (notes: string) => void;
  clearCart: () => void;
  getSubtotal: () => number;
  getTotal: () => number;
}

export const useCartStore = create<CartState>((set, get) => ({
  items: [],
  orderType: 'dine_in',
  tableId: null,
  customerId: null,
  deliveryZoneId: null,
  deliveryAddress: '',
  couponCode: null,
  discountAmount: 0,
  discountType: null,
  notes: '',

  addItem: (item) => {
    const id = crypto.randomUUID();
    set((state) => ({
      items: [...state.items, { ...item, id }],
    }));
  },

  removeItem: (id) => {
    set((state) => ({
      items: state.items.filter((i) => i.id !== id),
    }));
  },

  updateQuantity: (id, quantity) => {
    if (quantity <= 0) {
      get().removeItem(id);
      return;
    }
    set((state) => ({
      items: state.items.map((i) => (i.id === id ? { ...i, quantity } : i)),
    }));
  },

  setOrderType: (type) => set({ orderType: type }),
  setTableId: (id) => set({ tableId: id }),
  setCustomerId: (id) => set({ customerId: id }),
  setDeliveryZoneId: (id) => set({ deliveryZoneId: id }),
  setDeliveryAddress: (address) => set({ deliveryAddress: address }),
  setCoupon: (code) => set({ couponCode: code }),
  setDiscount: (amount, type) => set({ discountAmount: amount, discountType: type }),
  setNotes: (notes) => set({ notes }),

  clearCart: () => set({
    items: [],
    tableId: null,
    customerId: null,
    deliveryZoneId: null,
    deliveryAddress: '',
    couponCode: null,
    discountAmount: 0,
    discountType: null,
    notes: '',
  }),

  getSubtotal: () => {
    const { items } = get();
    return items.reduce((sum, item) => {
      const modifiersTotal = item.modifiers.reduce((m, mod) => m + mod.price, 0);
      return sum + (item.unit_price + modifiersTotal) * item.quantity;
    }, 0);
  },

  getTotal: () => {
    const subtotal = get().getSubtotal();
    const { discountAmount, discountType } = get();
    let total = subtotal;
    if (discountType === 'percentage') {
      total -= subtotal * (discountAmount / 100);
    } else if (discountType === 'fixed') {
      total -= discountAmount;
    }
    return Math.max(0, total);
  },
}));
