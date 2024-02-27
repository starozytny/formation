import React from "react";

export function ButtonACancel ({ link, children = "Annuler" })
{
	return <a href={link}
			  className="inline-flex justify-center text-center rounded-md bg-white py-2 px-4 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
		{children}
	</a>
}

export function ButtonSubmit ({ children })
{
	return <button type="submit"
				   className="inline-flex justify-center text-center rounded-md bg-blue-600 py-2 px-4 text-sm font-semibold text-slate-50 shadow-sm ring-1 ring-inset ring-blue-600 hover:bg-blue-500"
	>
		{children}
	</button>
}

export function Button ({ colorBg, colorBgHover, colorText, ring, colorRing, children, onClick }) {
	return <button type="button" onClick={onClick}
				   className={`inline-flex justify-center rounded-md bg-${colorBg} py-2 px-4 text-sm font-semibold text-${colorText} shadow-sm ${ring ? `ring-1 ring-inset ring-${colorRing}` : ''} hover:bg-${colorBgHover}`}>
		{children}
	</button>
}

export function ButtonIconA ({ icon, link, children }) {
	return <a href={link}
			  className="relative inline-flex justify-center rounded-md bg-white text-lg px-2 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
		<span className={`icon-${icon} text-gray-600`}></span>
		<span className="tooltip bg-gray-300 py-1 px-2 rounded absolute -top-7 right-0 text-xs hidden">{children}</span>
	</a>
}

export function ButtonIcon ({ icon, onClick, children })
{
	return <button onClick={onClick}
				   className="relative inline-flex justify-center rounded-md bg-white text-lg px-2 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
		<span className={`icon-${icon} text-gray-600`}></span>
		<span className="tooltip bg-gray-300 py-1 px-2 rounded absolute -top-7 right-0 text-xs hidden">{children}</span>
	</button>
}
