import React from "react";
import PropTypes from "prop-types";

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

export function ButtonA ({ type, width, children, iconLeft, iconRight, link })
{
	const colorVariants = {
		red: 'bg-red-600 text-slate-50 hover:bg-red-500',
		blue: 'bg-blue-600 text-slate-50 hover:bg-blue-500 ring-1 ring-inset ring-gray-600',
		default: 'bg-white text-gray-900 hover:bg-gray-50 ring-1 ring-inset ring-gray-300',
	}

	return <a href={link}
			  className={`inline-flex justify-center ${width} rounded-md py-2 px-4 text-sm font-semibold shadow-sm ${colorVariants[type]}`}>
		{iconLeft ? <span className={`icon-${iconLeft} inline-block pr-1 translate-y-0.5`}></span> : null}
		<span>{children}</span>
		{iconRight ? <span className={`icon-${iconRight} inline-block pl-1 translate-y-0.5`}></span> : null}
	</a>
}

export function Button ({ type, width, children, iconLeft, iconRight, onClick })
{
	const colorVariants = {
		red: 'bg-red-600 text-slate-50 hover:bg-red-500',
		blue: 'bg-blue-600 text-slate-50 hover:bg-blue-500 ring-1 ring-inset ring-gray-600',
		default: 'bg-white text-gray-900 hover:bg-gray-50 ring-1 ring-inset ring-gray-300',
	}

	return <button type="button" onClick={onClick}
				   className={`inline-flex justify-center ${width} rounded-md py-2 px-4 text-sm font-semibold shadow-sm ${colorVariants[type]}`}>
		{iconLeft ? <span className={`icon-${iconLeft} inline-block pr-1 translate-y-0.5`}></span> : null}
		<span>{children}</span>
		{iconRight ? <span className={`icon-${iconRight} inline-block pl-1 translate-y-0.5`}></span> : null}
	</button>
}

ButtonA.propTypes = {
	type: PropTypes.string,
	width: PropTypes.string,
	iconLeft: PropTypes.string,
	iconRight: PropTypes.string,
	onClick: PropTypes.oneOfType([
		PropTypes.node,
		PropTypes.func,
	]),
}

Button.propTypes = {
	type: PropTypes.string,
	width: PropTypes.string,
	iconLeft: PropTypes.string,
	iconRight: PropTypes.string,
	onClick: PropTypes.oneOfType([
		PropTypes.node,
		PropTypes.func,
	]),
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
