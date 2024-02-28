import React from 'react';

export function Badge ({ type, children })
{
	const typeVariants = {
		gray: 'bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-500/10',
	}

	return <span className={`inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ${typeVariants[type]}`}>
		{children}
	</span>
}
