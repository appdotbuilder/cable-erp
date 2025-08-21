import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Cable ERP System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6 text-gray-800 lg:justify-center lg:p-8 dark:from-gray-900 dark:to-gray-800 dark:text-gray-200">
                <header className="mb-8 w-full max-w-6xl">
                    <nav className="flex items-center justify-between">
                        <div className="flex items-center space-x-2">
                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-white text-xl font-bold">
                                📦
                            </div>
                            <span className="text-xl font-semibold text-gray-800 dark:text-gray-200">Cable ERP</span>
                        </div>
                        <div className="flex items-center gap-4">
                            {auth.user ? (
                                <Link
                                    href={route('dashboard')}
                                    className="inline-flex items-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Go to Dashboard
                                </Link>
                            ) : (
                                <>
                                    <Link
                                        href={route('login')}
                                        className="inline-flex items-center rounded-lg border border-gray-300 px-5 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:text-white dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"
                                    >
                                        Log in
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center rounded-lg bg-blue-600 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    >
                                        Get Started
                                    </Link>
                                </>
                            )}
                        </div>
                    </nav>
                </header>

                <main className="w-full max-w-6xl">
                    {/* Hero Section */}
                    <div className="text-center mb-16">
                        <h1 className="mb-6 text-5xl font-bold tracking-tight text-gray-900 dark:text-white">
                            🏭 Cable Company ERP System
                        </h1>
                        <p className="mb-8 text-xl text-gray-600 max-w-3xl mx-auto dark:text-gray-300">
                            Complete Enterprise Resource Planning solution for cable manufacturers and distributors. 
                            Manage inventory with barcode tracking, handle customer relations, and streamline accounting operations.
                        </p>
                        {!auth.user && (
                            <div className="flex justify-center gap-4">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center px-8 py-3 text-lg font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Start Free Trial
                                </Link>
                                <Link
                                    href={route('login')}
                                    className="inline-flex items-center px-8 py-3 text-lg font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-700"
                                >
                                    Sign In
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Features Grid */}
                    <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                        {/* Inventory Management */}
                        <div className="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="flex items-center mb-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-green-100 text-green-600 text-2xl dark:bg-green-900 dark:text-green-400">
                                    📦
                                </div>
                                <h3 className="ml-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Inventory Management
                                </h3>
                            </div>
                            <ul className="space-y-2 text-gray-600 dark:text-gray-300">
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Barcode scanning & tracking
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Cable specifications management
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Stock movement history
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Low stock alerts
                                </li>
                            </ul>
                        </div>

                        {/* Sales & Invoicing */}
                        <div className="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="flex items-center mb-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-100 text-blue-600 text-2xl dark:bg-blue-900 dark:text-blue-400">
                                    💰
                                </div>
                                <h3 className="ml-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Sales & Invoicing
                                </h3>
                            </div>
                            <ul className="space-y-2 text-gray-600 dark:text-gray-300">
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Automated invoice generation
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Payment tracking
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Accounts receivable
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Overdue notifications
                                </li>
                            </ul>
                        </div>

                        {/* Customer Management */}
                        <div className="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="flex items-center mb-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-100 text-purple-600 text-2xl dark:bg-purple-900 dark:text-purple-400">
                                    👥
                                </div>
                                <h3 className="ml-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Customer Relations
                                </h3>
                            </div>
                            <ul className="space-y-2 text-gray-600 dark:text-gray-300">
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Customer database
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Payment terms management
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Credit limit tracking
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">✅</span>
                                    Purchase history
                                </li>
                            </ul>
                        </div>

                        {/* User Roles */}
                        <div className="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="flex items-center mb-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-orange-100 text-orange-600 text-2xl dark:bg-orange-900 dark:text-orange-400">
                                    🔐
                                </div>
                                <h3 className="ml-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Role-Based Access
                                </h3>
                            </div>
                            <ul className="space-y-2 text-gray-600 dark:text-gray-300">
                                <li className="flex items-center">
                                    <span className="mr-2">👔</span>
                                    Administrator access
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">📦</span>
                                    Inventory manager
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">💼</span>
                                    Accountant role
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">🤝</span>
                                    Sales staff
                                </li>
                            </ul>
                        </div>

                        {/* Reporting */}
                        <div className="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="flex items-center mb-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-red-100 text-red-600 text-2xl dark:bg-red-900 dark:text-red-400">
                                    📊
                                </div>
                                <h3 className="ml-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Analytics & Reports
                                </h3>
                            </div>
                            <ul className="space-y-2 text-gray-600 dark:text-gray-300">
                                <li className="flex items-center">
                                    <span className="mr-2">📈</span>
                                    Sales performance
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">📉</span>
                                    Inventory levels
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">💹</span>
                                    Financial overview
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">⚡</span>
                                    Real-time insights
                                </li>
                            </ul>
                        </div>

                        {/* Integration */}
                        <div className="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow dark:bg-gray-800">
                            <div className="flex items-center mb-4">
                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 text-2xl dark:bg-indigo-900 dark:text-indigo-400">
                                    🔗
                                </div>
                                <h3 className="ml-4 text-xl font-semibold text-gray-900 dark:text-white">
                                    Modern Features
                                </h3>
                            </div>
                            <ul className="space-y-2 text-gray-600 dark:text-gray-300">
                                <li className="flex items-center">
                                    <span className="mr-2">📱</span>
                                    Mobile-friendly interface
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">☁️</span>
                                    Cloud-based system
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">🔄</span>
                                    Real-time updates
                                </li>
                                <li className="flex items-center">
                                    <span className="mr-2">🛡️</span>
                                    Secure & reliable
                                </li>
                            </ul>
                        </div>
                    </div>

                    {/* CTA Section */}
                    {!auth.user && (
                        <div className="text-center bg-white rounded-xl p-12 shadow-lg dark:bg-gray-800">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4 dark:text-white">
                                Ready to Transform Your Cable Business? 🚀
                            </h2>
                            <p className="text-xl text-gray-600 mb-8 max-w-2xl mx-auto dark:text-gray-300">
                                Join industry leaders who trust our ERP system to manage their cable inventory, 
                                streamline operations, and boost profitability.
                            </p>
                            <div className="flex justify-center gap-4">
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center px-8 py-4 text-lg font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                >
                                    Start Your Free Trial Today
                                </Link>
                            </div>
                        </div>
                    )}
                </main>

                <footer className="mt-16 text-center text-sm text-gray-500 dark:text-gray-400">
                    <p>
                        Built with ❤️ using Laravel + React + Inertia.js | © 2024 Cable ERP System
                    </p>
                </footer>
            </div>
        </>
    );
}