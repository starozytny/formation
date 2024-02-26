import React from "react";

/***************************************
 * INPUT Classique
 ***************************************/
export function Input (props) {
	const { type="text", identifiant, valeur, errors, onChange, children, placeholder="", autocomplete="on", password=false } = props;

	let error;
	if (errors && errors.length !== 0) {
		errors.map(err => {
			if (err.name === identifiant) {
				error = err.message
			}
		})
	}

	let styleInput = "block w-full rounded-md border-0 py-2 pl-3 pr-20 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-gray-500 xl:text-sm sm:leading-6";

	return <>
		<label htmlFor={identifiant} className="block mb-1 xl:text-sm font-medium leading-6 text-gray-900">
			{children}
		</label>
		<div className="relative rounded-md shadow-sm">
			<input type={type} name={identifiant} id={identifiant} value={valeur}
				   placeholder={placeholder} onChange={onChange} autoComplete={autocomplete}
				   className={styleInput + " " + (error ? "ring-red-400" : "ring-gray-300")} />
		</div>
		{error ? <div className="text-red-500 mt-1 text-base xl:text-sm">
			<span className="icon-error inline-block translate-y-0.5" />
			<span className="ml-1">{error}</span>
		</div> : null}
	</>
}
