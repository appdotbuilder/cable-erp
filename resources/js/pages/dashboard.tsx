import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

interface DashboardProps {
    inventoryStats: {
        total_cables: number;
        active_cables: number;
        low_stock_cables: number;
        total_inventory_value: number;
    };
    salesStats: {
        total_customers: number;
        active_customers: number;
        total_invoices: number;
        total_sales: number;
        outstanding_amount: number;
        overdue_invoices: number;
    };
    recentStockMovements: Array<{
        id: number;
        type: string;
        quantity: number;
        movement_date: string;
        cable: { name: string; barcode: string };
        user: { name: string };
    }>;
    recentInvoices: Array<{
        id: number;
        invoice_number: string;
        total_amount: number;
        status: string;
        invoice_date: string;
        customer: { name: string; company?: string };
    }>;
    lowStockCables: Array<{
        id: number;
        name: string;
        barcode: string;
        stock_quantity: number;
        minimum_stock: number;
    }>;
    overdueInvoices: Array<{
        id: number;
        invoice_number: string;
        total_amount: number;
        due_date: string;
        customer: { name: string; company?: string };
    }>;
    [key: string]: unknown;
}

export default function Dashboard({ 
    inventoryStats, 
    salesStats, 
    recentStockMovements, 
    recentInvoices,
    lowStockCables,
    overdueInvoices 
}: DashboardProps) {
    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(amount);
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('id-ID');
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-6 p-6">
                {/* Header */}
                <div>
                    <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                        📊 ERP Dashboard
                    </h1>
                    <p className="text-gray-600 dark:text-gray-400 mt-1">
                        Welcome to your Cable Company ERP system
                    </p>
                </div>

                {/* Stats Overview */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {/* Inventory Stats */}
                    <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Total Cables
                                </p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                    {inventoryStats.total_cables}
                                </p>
                            </div>
                            <div className="p-3 bg-blue-100 rounded-full dark:bg-blue-900">
                                <span className="text-2xl">📦</span>
                            </div>
                        </div>
                        <Link 
                            href={route('cables.index')} 
                            className="text-blue-600 hover:underline text-sm mt-2 inline-block"
                        >
                            View Inventory →
                        </Link>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Low Stock Items
                                </p>
                                <p className="text-2xl font-bold text-red-600">
                                    {inventoryStats.low_stock_cables}
                                </p>
                            </div>
                            <div className="p-3 bg-red-100 rounded-full dark:bg-red-900">
                                <span className="text-2xl">⚠️</span>
                            </div>
                        </div>
                        <Link 
                            href={route('cables.index', { low_stock: 1 })} 
                            className="text-red-600 hover:underline text-sm mt-2 inline-block"
                        >
                            View Low Stock →
                        </Link>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Total Customers
                                </p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-white">
                                    {salesStats.total_customers}
                                </p>
                            </div>
                            <div className="p-3 bg-green-100 rounded-full dark:bg-green-900">
                                <span className="text-2xl">👥</span>
                            </div>
                        </div>
                        <Link 
                            href={route('customers.index')} 
                            className="text-green-600 hover:underline text-sm mt-2 inline-block"
                        >
                            View Customers →
                        </Link>
                    </div>

                    <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Outstanding Amount
                                </p>
                                <p className="text-2xl font-bold text-orange-600">
                                    {formatCurrency(salesStats.outstanding_amount)}
                                </p>
                            </div>
                            <div className="p-3 bg-orange-100 rounded-full dark:bg-orange-900">
                                <span className="text-2xl">💰</span>
                            </div>
                        </div>
                        <Link 
                            href={route('invoices.index', { status: 'sent' })} 
                            className="text-orange-600 hover:underline text-sm mt-2 inline-block"
                        >
                            View Outstanding →
                        </Link>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                    <h2 className="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                        🚀 Quick Actions
                    </h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <Link
                            href={route('cables.create')}
                            className="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-colors dark:border-gray-600 dark:hover:border-blue-400 dark:hover:bg-blue-900/20"
                        >
                            <span className="text-2xl mr-3">📦</span>
                            <span className="font-medium text-gray-700 dark:text-gray-300">Add New Cable</span>
                        </Link>
                        
                        <Link
                            href={route('stock-movements.create')}
                            className="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-green-500 hover:bg-green-50 transition-colors dark:border-gray-600 dark:hover:border-green-400 dark:hover:bg-green-900/20"
                        >
                            <span className="text-2xl mr-3">📊</span>
                            <span className="font-medium text-gray-700 dark:text-gray-300">Record Stock Movement</span>
                        </Link>
                        
                        <Link
                            href={route('customers.create')}
                            className="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-colors dark:border-gray-600 dark:hover:border-purple-400 dark:hover:bg-purple-900/20"
                        >
                            <span className="text-2xl mr-3">👤</span>
                            <span className="font-medium text-gray-700 dark:text-gray-300">Add Customer</span>
                        </Link>
                        
                        <Link
                            href={route('invoices.create')}
                            className="flex items-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-colors dark:border-gray-600 dark:hover:border-orange-400 dark:hover:bg-orange-900/20"
                        >
                            <span className="text-2xl mr-3">📄</span>
                            <span className="font-medium text-gray-700 dark:text-gray-300">Create Invoice</span>
                        </Link>
                    </div>
                </div>

                {/* Recent Activity & Alerts */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Recent Stock Movements */}
                    <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                                📊 Recent Stock Movements
                            </h2>
                            <Link 
                                href={route('stock-movements.index')} 
                                className="text-blue-600 hover:underline text-sm"
                            >
                                View All
                            </Link>
                        </div>
                        <div className="space-y-3">
                            {recentStockMovements.slice(0, 5).map((movement) => (
                                <div key={movement.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg dark:bg-gray-700">
                                    <div>
                                        <p className="font-medium text-gray-900 dark:text-white">
                                            {movement.cable.name}
                                        </p>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            {movement.type === 'in' ? '+' : '-'}{Math.abs(movement.quantity)} units
                                        </p>
                                    </div>
                                    <span className={`px-2 py-1 rounded text-xs font-medium ${
                                        movement.type === 'in' 
                                            ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                            : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                    }`}>
                                        {movement.type.toUpperCase()}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Low Stock Alerts */}
                    <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                                ⚠️ Low Stock Alerts
                            </h2>
                            <Link 
                                href={route('cables.index', { low_stock: 1 })} 
                                className="text-red-600 hover:underline text-sm"
                            >
                                View All
                            </Link>
                        </div>
                        <div className="space-y-3">
                            {lowStockCables.slice(0, 5).map((cable) => (
                                <div key={cable.id} className="flex items-center justify-between p-3 bg-red-50 rounded-lg dark:bg-red-900/20">
                                    <div>
                                        <p className="font-medium text-gray-900 dark:text-white">
                                            {cable.name}
                                        </p>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            Stock: {cable.stock_quantity} / Min: {cable.minimum_stock}
                                        </p>
                                    </div>
                                    <span className="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium dark:bg-red-900 dark:text-red-300">
                                        LOW
                                    </span>
                                </div>
                            ))}
                            {lowStockCables.length === 0 && (
                                <p className="text-gray-500 text-center py-4 dark:text-gray-400">
                                    🎉 All cables are well stocked!
                                </p>
                            )}
                        </div>
                    </div>
                </div>

                {/* Recent Invoices & Overdue */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {/* Recent Invoices */}
                    <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                                📄 Recent Invoices
                            </h2>
                            <Link 
                                href={route('invoices.index')} 
                                className="text-blue-600 hover:underline text-sm"
                            >
                                View All
                            </Link>
                        </div>
                        <div className="space-y-3">
                            {recentInvoices.slice(0, 5).map((invoice) => (
                                <div key={invoice.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg dark:bg-gray-700">
                                    <div>
                                        <p className="font-medium text-gray-900 dark:text-white">
                                            {invoice.invoice_number}
                                        </p>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            {invoice.customer.company || invoice.customer.name}
                                        </p>
                                    </div>
                                    <div className="text-right">
                                        <p className="font-semibold text-gray-900 dark:text-white">
                                            {formatCurrency(invoice.total_amount)}
                                        </p>
                                        <span className={`px-2 py-1 rounded text-xs font-medium ${
                                            invoice.status === 'paid' 
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
                                        }`}>
                                            {invoice.status.toUpperCase()}
                                        </span>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Overdue Invoices */}
                    <div className="bg-white rounded-lg shadow p-6 dark:bg-gray-800">
                        <div className="flex items-center justify-between mb-4">
                            <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
                                🚨 Overdue Invoices
                            </h2>
                            <Link 
                                href={route('invoices.index', { overdue: 1 })} 
                                className="text-red-600 hover:underline text-sm"
                            >
                                View All
                            </Link>
                        </div>
                        <div className="space-y-3">
                            {overdueInvoices.slice(0, 5).map((invoice) => (
                                <div key={invoice.id} className="flex items-center justify-between p-3 bg-red-50 rounded-lg dark:bg-red-900/20">
                                    <div>
                                        <p className="font-medium text-gray-900 dark:text-white">
                                            {invoice.invoice_number}
                                        </p>
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            {invoice.customer.company || invoice.customer.name}
                                        </p>
                                        <p className="text-xs text-red-600 dark:text-red-400">
                                            Due: {formatDate(invoice.due_date)}
                                        </p>
                                    </div>
                                    <div className="text-right">
                                        <p className="font-semibold text-red-600">
                                            {formatCurrency(invoice.total_amount)}
                                        </p>
                                        <span className="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium dark:bg-red-900 dark:text-red-300">
                                            OVERDUE
                                        </span>
                                    </div>
                                </div>
                            ))}
                            {overdueInvoices.length === 0 && (
                                <p className="text-gray-500 text-center py-4 dark:text-gray-400">
                                    ✅ No overdue invoices!
                                </p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}