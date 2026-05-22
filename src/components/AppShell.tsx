import React, { useState } from 'react';
import { NavLink } from 'react-router-dom';
import { useAuth } from '../lib/auth';
import {
  ShoppingCart, ClipboardList, UtensilsCrossed, LayoutDashboard,
  Users, Package, BookOpen, UserCog, Clock, Wallet, BarChart3,
  Settings, MapPin, Ticket, LogOut, Menu, X
} from 'lucide-react';

interface NavItem {
  path: string;
  label: string;
  icon: React.ReactNode;
  requiredRole?: 'manager' | 'super_admin';
}

const navItems: NavItem[] = [
  { path: '/pos', label: 'نقطة البيع', icon: <ShoppingCart size={20} /> },
  { path: '/orders', label: 'الطلبات', icon: <ClipboardList size={20} /> },
  { path: '/tables', label: 'الطاولات', icon: <UtensilsCrossed size={20} /> },
  { path: '/dashboard', label: 'لوحة التحكم', icon: <LayoutDashboard size={20} /> },
  { path: '/menu', label: 'المنيو', icon: <BookOpen size={20} />, requiredRole: 'manager' },
  { path: '/customers', label: 'العملاء', icon: <Users size={20} /> },
  { path: '/delivery-zones', label: 'مناطق التوصيل', icon: <MapPin size={20} />, requiredRole: 'manager' },
  { path: '/coupons', label: 'الكوبونات', icon: <Ticket size={20} />, requiredRole: 'manager' },
  { path: '/inventory', label: 'المخزون', icon: <Package size={20} />, requiredRole: 'manager' },
  { path: '/recipes', label: 'الوصفات', icon: <BookOpen size={20} />, requiredRole: 'manager' },
  { path: '/employees', label: 'الموظفين', icon: <UserCog size={20} />, requiredRole: 'manager' },
  { path: '/shifts', label: 'الورديات', icon: <Clock size={20} /> },
  { path: '/expenses', label: 'المصروفات', icon: <Wallet size={20} />, requiredRole: 'manager' },
  { path: '/reports', label: 'التقارير', icon: <BarChart3 size={20} />, requiredRole: 'manager' },
  { path: '/settings/receipt', label: 'الإعدادات', icon: <Settings size={20} />, requiredRole: 'manager' },
];

export function AppShell({ children }: { children: React.ReactNode }) {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const { signOut, user, hasPermission } = useAuth();

  const filteredNav = navItems.filter(item => {
    if (!item.requiredRole) return true;
    return hasPermission(item.requiredRole);
  });

  return (
    <div className="flex h-screen overflow-hidden bg-gray-50">
      {/* Sidebar */}
      <aside className={`${sidebarOpen ? 'w-64' : 'w-16'} bg-white border-l border-gray-200 flex flex-col transition-all duration-300 shrink-0`}>
        {/* Logo */}
        <div className="h-16 flex items-center justify-between px-4 border-b border-gray-100">
          {sidebarOpen && <h1 className="text-xl font-bold text-primary-600">ستي POS</h1>}
          <button onClick={() => setSidebarOpen(!sidebarOpen)} className="p-1.5 rounded-lg hover:bg-gray-100">
            {sidebarOpen ? <X size={20} /> : <Menu size={20} />}
          </button>
        </div>

        {/* Nav */}
        <nav className="flex-1 overflow-y-auto py-2 px-2 space-y-1">
          {filteredNav.map((item) => (
            <NavLink
              key={item.path}
              to={item.path}
              className={({ isActive }) =>
                `flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors ${
                  isActive
                    ? 'bg-primary-50 text-primary-700'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'
                }`
              }
            >
              {item.icon}
              {sidebarOpen && <span>{item.label}</span>}
            </NavLink>
          ))}
        </nav>

        {/* Footer */}
        <div className="border-t border-gray-100 p-3 space-y-2">
          {/* Current user */}
          {sidebarOpen && user && (
            <div className="text-xs text-gray-500 px-3 py-1">
              {user.name} ({user.role === 'super_admin' ? 'مدير' : user.role === 'manager' ? 'مشرف' : 'كاشير'})
            </div>
          )}

          {/* Logout */}
          <button
            onClick={signOut}
            className="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50 transition-colors"
          >
            <LogOut size={18} />
            {sidebarOpen && <span>تسجيل خروج</span>}
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 overflow-y-auto">
        <div className="p-6">
          {children}
        </div>
      </main>
    </div>
  );
}
