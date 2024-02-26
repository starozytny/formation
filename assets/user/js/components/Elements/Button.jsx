import React from "react";

export function ButtonACancel ({ link, children = "Annuler" })
{
	return <a href={link}
			  className="inline-flex justify-center text-center rounded-md bg-white py-2 px-4 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
		{children}
	</a>
}

export function ButtonSubmit ({ children }) {
	return <button type="submit"
				   className="inline-flex justify-center text-center rounded-md bg-blue-600 py-2 px-4 text-sm font-semibold text-slate-50 shadow-sm ring-1 ring-inset ring-blue-600 hover:bg-blue-500"
	>
		{children}
	</button>
}
