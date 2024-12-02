import Pagination from '@/Components/Pagination';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, useForm, usePage } from '@inertiajs/react';
import React, { useEffect, useState } from 'react';

export default function Edot({ auth, expense }) {

	const { flash } = usePage().props;

	const { data, setData, post, processing, errors } = useForm({
		expense_id: expense.data.expense_id,
		account_name: expense.data.account_name,
		currency_code: expense.data.currency_code,
		description: expense.data.description,
		customer_name: expense.data.customer_name,
		total: expense.data.total,
		paid_through_account_name: expense.data.paid_through_account_name,
		receipt: null,
	});

	const submit = (e) => {
		e.preventDefault();
		if (!confirm('Are you sure?')) return;
		post(route('expenses.update.receipt', { id: expense.data.id }));
	};


	return (
		<AuthenticatedLayout
			user={auth.user}
			header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Add Receipt</h2>}
		>
			<Head title="Add Receipt to Expense" />

			<div className="py-12">
				<div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
					<div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
						<div className="p-6 text-gray-900">
							<div className="mb-4 flex justify-between items-center">
								<div className="headSection">
									{flash.message && (
										<div className="mb-4 rounded-md bg-green-400 px-3 py-2 text-white">{flash.message}</div>
									)}
									{flash.error && (
										<div className="mb-4 rounded-md bg-red-400 px-3 py-2 text-white">{flash.error}</div>
									)}
								</div>
							</div>
							<div>
								<form onSubmit={submit}>
									<div>
										<label htmlFor="account_name" className="block text-sm font-medium text-gray-700">Account Name</label>
										<input
											readOnly
											value={expense.data.account_name}
											onChange={(e) => setData('account_name', e.target.value)}
											type="text"
											id="account_name"
											className="mb-4 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-not-allowed bg-gray-100"
										/>
										{errors.account_name && (<p className="mt-2 text-sm text-red-600">{errors.account_name}</p>)}
									</div>

									<div>
										<label htmlFor="currency_code" className="block text-sm font-medium text-gray-700">Currency Code</label>
										<input
											readOnly
											value={expense.data.currency_code}
											onChange={(e) => setData('currency_code', e.target.value)}
											type="text"
											id="currency_code"
											className="mb-4 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-not-allowed bg-gray-100"
										/>
										{errors.currency_code && (<p className="mt-2 text-sm text-red-600">{errors.currency_code}</p>)}
									</div>

									<div>
										<label htmlFor="description" className="block text-sm font-medium text-gray-700">Description</label>
										<input
											readOnly
											value={expense.data.description}
											onChange={(e) => setData('description', e.target.value)}
											type="text"
											id="description"
											className="mb-4 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-not-allowed bg-gray-100"
										/>
										{errors.description && (<p className="mt-2 text-sm text-red-600">{errors.description}</p>)}
									</div>

									<div>
										<label htmlFor="customer_name" className="block text-sm font-medium text-gray-700">Customer Name</label>
										<input
											readOnly
											value={expense.data.customer_name}
											onChange={(e) => setData('customer_name', e.target.value)}
											type="text"
											id="customer_name"
											className="mb-4 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-not-allowed bg-gray-100"
										/>
										{errors.customer_name && (<p className="mt-2 text-sm text-red-600">{errors.customer_name}</p>)}
									</div>

									<div>
										<label htmlFor="total" className="block text-sm font-medium text-gray-700">Total</label>
										<input
											readOnly
											value={expense.data.total}
											onChange={(e) => setData('total', e.target.value)}
											type="text"
											id="total"
											className="mb-4 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-not-allowed bg-gray-100"
										/>
										{errors.total && (<p className="mt-2 text-sm text-red-600">{errors.total}</p>)}
									</div>

									<div>
										<label htmlFor="paid_through_account_name" className="block text-sm font-medium text-gray-700">Paid Through Account Name</label>
										<input
											readOnly
											value={expense.data.paid_through_account_name}
											onChange={(e) => setData('paid_through_account_name', e.target.value)}
											type="text"
											id="paid_through_account_name"
											className="mb-4 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 cursor-not-allowed bg-gray-100"
										/>
										{errors.paid_through_account_name && (<p className="mt-2 text-sm text-red-600">{errors.paid_through_account_name}</p>)}
									</div>

									<div className="space-y-2">
										<label htmlFor="receipt" className="block text-sm font-medium text-gray-700">Upload Receipt</label>
										<div className="flex items-center space-x-4">
											{/* <input type="file" onChange={e => setData('receipt', e.target.files[0])} />
											{errors.receipt && <div className="text-danger">{errors.receipt}</div>} */}

											<input
												type="file"
												onChange={e => setData('receipt', e.target.files[0])}
												id="receipt"
												className="block w-full text-sm text-gray-900 border border-gray-300 rounded-md shadow-sm file:border-none file:bg-indigo-500 file:text-white file:px-4 file:py-2 file:rounded-md hover:file:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
											/>
											<span className="text-sm text-gray-500">Allowed Extension: gif, png, jpeg, jpg, bmp, pdf, xls, xlsx, doc and docx.</span>
										</div>
										{errors.receipt && <div className="mt-2 text-sm text-red-600">{errors.receipt}</div>}
									</div>



									<div className="mt-4 py-4 space-x-2">
										<button disabled={processing} type="submit" className="inline-block rounded-md bg-blue-500 px-4 py-3 text-xs font-semibold uppercase tracking-widest text-white shadow-sm disabled:opacity-25">
											Add Receipt
										</button>
										<Link href={route('expenses')} className="inline-block rounded-md border border-gray-300 px-4 py-3 text-xs font-semibold uppercase tracking-widest shadow-sm">
											Cancel
										</Link>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</AuthenticatedLayout>
	);
}
