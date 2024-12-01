import { router } from "@inertiajs/react";
import React from "react";

const Pagination = ({ links, currentPage, setCurrentPage }) => {
	const handlePageChange = (url) => {
		const pageParam = new URL(url).searchParams.get('page');
		setCurrentPage(pageParam);
		router.get(url, { preserveState: true });
	};

	return (
		<nav aria-label="Page navigation example" className="mt-4">
			<ul className="flex justify-center space-x-2">
				{links.map((link, index) => (
					<li
						key={index}
						className={`${link.active
								? "bg-blue-500 text-white border-blue-500"
								: "bg-white text-gray-500 border-gray-300"
							} border rounded-md`}
					>
						<a
							className="px-4 py-2 block hover:bg-blue-100 focus:ring-2 focus:ring-blue-300"
							href={link.url}
							onClick={(e) => {
								e.preventDefault();
								handlePageChange(link.url);
							}}
							dangerouslySetInnerHTML={{ __html: link.label }}
						></a>
					</li>
				))}
			</ul>
		</nav>
	);
};

export default Pagination;
