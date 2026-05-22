import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from './lib/auth';
import { AppShell } from './components/AppShell';
import { LoginPage } from './routes/login';
import { POSPage } from './routes/pos';
import { OrdersPage } from './routes/orders/index';
import { OrderDetailPage } from './routes/orders/detail';
import { MenuPage } from './routes/menu';
import { TablesPage } from './routes/tables';
import { CustomersPage } from './routes/customers';
import { InventoryPage } from './routes/inventory';
import { RecipesPage } from './routes/recipes';
import { EmployeesPage } from './routes/employees';
import { ShiftsPage } from './routes/shifts';
import { ExpensesPage } from './routes/expenses';
import { DashboardPage } from './routes/dashboard';
import { ReportsPage } from './routes/reports';
import { SettingsReceiptPage } from './routes/settings/receipt';
import { DeliveryZonesPage } from './routes/delivery-zones';
import { CouponsPage } from './routes/coupons';
import { Toaster } from 'sonner';

function ProtectedRoute({ children }: { children: React.ReactNode }) {
  const { user, loading } = useAuth();
  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto mb-4"></div>
          <p className="text-gray-500">جاري التحميل...</p>
        </div>
      </div>
    );
  }
  if (!user) return <Navigate to="/login" replace />;
  return <>{children}</>;
}

export default function App() {
  return (
    <>
      <Toaster position="top-center" richColors dir="rtl" />
      <Routes>
        <Route path="/login" element={<LoginPage />} />
        <Route path="/" element={<Navigate to="/pos" replace />} />
        <Route
          path="/*"
          element={
            <ProtectedRoute>
              <AppShell>
                <Routes>
                  <Route path="/pos" element={<POSPage />} />
                  <Route path="/orders" element={<OrdersPage />} />
                  <Route path="/orders/:id" element={<OrderDetailPage />} />
                  <Route path="/menu" element={<MenuPage />} />
                  <Route path="/tables" element={<TablesPage />} />
                  <Route path="/customers" element={<CustomersPage />} />
                  <Route path="/inventory" element={<InventoryPage />} />
                  <Route path="/recipes" element={<RecipesPage />} />
                  <Route path="/employees" element={<EmployeesPage />} />
                  <Route path="/shifts" element={<ShiftsPage />} />
                  <Route path="/expenses" element={<ExpensesPage />} />
                  <Route path="/dashboard" element={<DashboardPage />} />
                  <Route path="/reports" element={<ReportsPage />} />
                  <Route path="/settings/receipt" element={<SettingsReceiptPage />} />
                  <Route path="/delivery-zones" element={<DeliveryZonesPage />} />
                  <Route path="/coupons" element={<CouponsPage />} />
                </Routes>
              </AppShell>
            </ProtectedRoute>
          }
        />
      </Routes>
    </>
  );
}
