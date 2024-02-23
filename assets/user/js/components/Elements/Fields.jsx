import React from "react";

/***************************************
 * INPUT Classique
 ***************************************/
export function Input (props) {
	const { type="text", identifiant, valeur, onChange, children, placeholder="", autocomplete="on", password=false } = props;

	let content = <>
		<div className="relative rounded-md shadow-sm">
			<input type={type} name={identifiant} id={identifiant} value={valeur}
				   placeholder={placeholder} onChange={onChange} autoComplete={autocomplete}
				   className="block w-full rounded-md border-0 py-2 pl-3 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-gray-500 xl:text-sm sm:leading-6" />
		</div>
	</>

	return <Structure {...props} content={content} label={children} />
}

/***************************************
 * STRUCTURE
 ***************************************/
export function Structure ({ identifiant, content, errors, label, noErrors = false }) {
	let error;
	if (!noErrors && errors && errors.length !== 0) {
		errors.map(err => {
			if (err.name === identifiant) {
				error = err.message
			}
		})
	}
	return <>
		<label htmlFor={identifiant} className="block mb-1 xl:text-sm font-medium leading-6 text-gray-900">
			{label}
		</label>
		{content}
		{!noErrors && <div className="error">
			{error ? <><span className='icon-error' />{error}</> : null}
		</div>}
	</>
}
