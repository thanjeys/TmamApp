import Pagination from '@/Components/Pagination';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, useForm, usePage } from '@inertiajs/react';
import React, { useEffect, useState } from 'react';

export default function Index({ auth, expenses, expenseAccountNames, paidAccountNames, formatted_last_synced_time }) {

	const [isSearchTriggered, setIsSearchTriggered] = useState(false);
	const { data, setData, get } = useForm({
		search: '',
		account_name: '',
		paid_through_account_name: '',
		page: expenses.current_page
	})

	const handleFilterChange = (e) => {
		setIsSearchTriggered(true);
		setData(e.target.name, e.target.value)
	};

	useEffect(() => {

		if (!isSearchTriggered) return;

		const timeoutId = setTimeout(() => {
			get('/expenses', {
				preserveState: true,
				search: data.search,
				page: data.page
			}, 300);
		})

		return () => clearTimeout(timeoutId);
	}, [data, isSearchTriggered]);

	const { flash } = usePage().props;
	const [isSyncing, setIsSyncing] = useState(false);

	const handleSync = () => {
		if (confirm('Are you sure?')) {
			setIsSyncing(true);
			router.post(route('sync.expenses'), {}, {
				onFinish: () => {
					setIsSyncing(false)
				}, // Reset loading state
			});
		}
	};


	return (
		<AuthenticatedLayout
			user={auth.user}
			header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Expenses</h2>}
		>
			<Head title="Expenses" />

			<div className="py-12">
				<div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
					<div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
						<div className="p-6 text-gray-900">
							<div className="mb-4 flex justify-between items-center">
								<div className="headSection">
									<button
										onClick={() => handleSync()}
										type="button"
										className={`rounded-md bg-blue-500 px-4 py-3 text-xs font-semibold uppercase tracking-widest text-white shadow-sm ${isSyncing ? 'opacity-50 cursor-not-allowed' : ''}`}
										disabled={isSyncing}
									>
										{isSyncing ? 'Syncing...' : 'Sync Expenses'}
									</button>
									<p className="mt-2 text-sm text-gray-600">
										Last Synced Time: {formatted_last_synced_time}
									</p>
									{flash.message && (
										<div className="mb-4 rounded-md bg-green-400 px-3 py-2 text-white">{flash.message}</div>
									)}
									{flash.error && (
										<div className="mb-4 rounded-md bg-red-400 px-3 py-2 text-white">{flash.error}</div>
									)}
								</div>

								<div className="ml-auto">
									<select
										name="account_name"
										value={data.account_name}
										onChange={handleFilterChange}
										className="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none w-64"
									>
										<option value="">Select Account</option>
										{expenseAccountNames.map((accountName, index) => (
											<option key={index} value={accountName}>
												{accountName}
											</option>
										))}
									</select>
								</div>

								<div className="ml-auto">
									<select
										name="paid_through_account_name"
										value={data.paid_through_account_name}
										onChange={handleFilterChange}
										className="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none w-64"
									>
										<option value="">Select Paid Account</option>
										{paidAccountNames.map((accountName, index) => (
											<option key={index} value={accountName}>
												{accountName}
											</option>
										))}
									</select>
								</div>
								<div className="ml-auto">
									<input
										type="text"
										name='search'
										value={data.search}
										placeholder="Search.."
										className="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none"
										onChange={handleFilterChange}
									/>
								</div>
							</div>
							<table className="min-w-full divide-y divide-gray-200 border">
								<thead>
									<tr>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Account Name</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Paid Through Account Name</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Currency</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Customer Name</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
										</th>
									</tr>
								</thead>
								<tbody className="bg-white divide-y divide-gray-200 divide-solid">
									{expenses.data && expenses.data.map((expense, index) => (
										<tr key={index}>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{expense.expense_id}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{expense.date}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{expense.account_name}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{expense.paid_through_account_name}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{expense.total}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{expense.currency_code}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{expense.customer_name}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">

												<Link href={route('expenses.update', expense.id)} className="mr-2 inline-block rounded-md bg-blue-500 px-3 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm">
													+Receipt</Link>
											</td>
										</tr>
									))}
								</tbody>
							</table>
							<Pagination
								links={expenses.meta.links}
								currentPage={expenses.currentPage}
								setCurrentPage={(page) => setData('page', page)}
							/>
						</div>
					</div>
				</div>
			</div>
		</AuthenticatedLayout>
	);
}
