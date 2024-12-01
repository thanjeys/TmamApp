import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function SyncLog({ auth }) {
	return (
		<AuthenticatedLayout
			user={auth.user}
			header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">SyncLogs</h2>}
		>
			<Head title="SyncLogs" />

			<div className="py-12">
				<div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
					<div className="bg-green-200 overflow-hidden shadow-sm sm:rounded-lg">
						<div className="p-6 text-gray-900">
							Note: This functionality will be available soon. Please visit later. Sorry for the inconvenience!
						</div>
					</div>
				</div>
			</div>
		</AuthenticatedLayout>
	);
}
