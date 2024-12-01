import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, usePage } from '@inertiajs/react';
import React, { useState } from 'react';

export default function Organization({ auth, organizations, formatted_last_synced_time }) {

	const { flash } = usePage().props;
	const [isSyncing, setIsSyncing] = useState(false);

	const handleSync = () => {
		if (confirm('Are you sure?')) {
			setIsSyncing(true);
			router.post(route('sync.orgs'), {}, {
				onFinish: () => {
					setIsSyncing(false)
				}, // Reset loading state
			});
		}
	};


	return (
		<AuthenticatedLayout
			user={auth.user}
			header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Organization</h2>}
		>
			<Head title="Organization" />

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
										{isSyncing ? 'Syncing...' : 'Sync Organizations'}
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

							</div>
							<table className="min-w-full divide-y divide-gray-200 border">
								<thead>
									<tr>

										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Name</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Contact Name</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</span>
										</th>
										<th className="px-6 py-3 bg-gray-50 text-left">
											<span className="text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Account Created Date</span>
										</th>
									</tr>
								</thead>
								<tbody className="bg-white divide-y divide-gray-200 divide-solid">
									{organizations.data && organizations.data.map((organization, index) => (
										<tr key={index}>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{organization.organization_id}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{organization.name}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{organization.contact_name}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{organization.email}
											</td>
											<td className="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
												{organization.account_created_date}
											</td>
										</tr>
									))}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</AuthenticatedLayout>
	);
}
