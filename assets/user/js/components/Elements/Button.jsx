import React from "react";

export function ButtonACancel ({ link, children = "Annuler" })
{
	return <a href={link}
			  className="inline-block rounded-md font-semibold py-2 px-4 text-center transition-colors hover:bg-gray-100">
		{children}
	</a>
}

export function ButtonSubmit ({ children }) {
	return <button type="submit"
				   className="inline-block rounded-md bg-blue-600 font-semibold py-2 px-4 text-center text-slate-50 transition-colors hover:bg-blue-500"
	>
		{children}
	</button>
}
