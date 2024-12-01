import Pagination from '@/Components/Pagination';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, useForm, usePage } from '@inertiajs/react';
import React, { useEffect, useState } from 'react';

export default function ChartOfAccount({ auth, accounts, formatted_last_synced_time }) {

	const [isSearchTriggered, setIsSearchTriggered] = useState(false);
	const { data, setData, get } = useForm({
		search: '',
		page: accounts.current_page
	})

	const handleFilterChange = (e) => {
		setIsSearchTriggered(true);
		setData(e.target.name, e.target.value)
	};

	useEffect(() => {

		if (!isSearchTriggered) return;

		const timeoutId = setTimeout(() => {
			console.log('handlefitler-use');
			get('/chart-of-accounts', {
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
			router.post(route('sync.chart.accounts'), {}, {
				onFinish: () => {
					setIsSyncing(false)
				}, // Reset loading state
			});
		}
	};


	return (
		<AuthenticatedLayout
			user={auth.user}
			header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Chart of Accounts</h2>}
		>
			<Head title="Chart of Accounts" />

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
										{isSyncing ? 'Syncing...' : 'Sync Chart Of Accounts'}
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
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Account Name</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Account Type</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Is Active</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Last Modified Time</span>
										</th>
									</tr>
								</thead>
								<tbody className="bg-white divide-y divide-gray-200 divide-solid">
									{accounts.data && accounts.data.map((account, index) => (
										<tr key={index}>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{account.account_id}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{account.account_name}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{account.account_type}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{account.is_active}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{account.last_modified_time}
											</td>
										</tr>
									))}
								</tbody>
							</table>
							<Pagination
								links={accounts.meta.links}
								currentPage={accounts.currentPage}
								setCurrentPage={(page) => setData('page', page)}
							/>
						</div>
					</div>
				</div>
			</div>
		</AuthenticatedLayout>
	);
}
