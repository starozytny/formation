import React, { useState } from "react";

/***************************************
 * INPUT Classique
 ***************************************/
export function Input (props) {
	const { type="text", identifiant, valeur, errors, onChange, children, placeholder="", autocomplete="on" } = props;

	const [showValue, setShowValue] = useState(false);

	let error;
	if (errors && errors.length !== 0) {
		errors.map(err => {
			if (err.name === identifiant) {
				error = err.message
			}
		})
	}

	let styleInput = "block w-full rounded-md border-0 py-2 pl-3 pr-20 text-gray-900 ring-1 ring-inset placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-gray-500 xl:text-sm sm:leading-6";

	let nType = type;
	if (showValue) {
		nType = "text";
	}

	return <>
		<label htmlFor={identifiant} className="block mb-1 xl:text-sm font-medium leading-6 text-gray-900">
			{children}
		</label>
		<div className="relative rounded-md shadow-sm">
			<input type={nType} name={identifiant} id={identifiant} value={valeur}
				   placeholder={placeholder} onChange={onChange} autoComplete={autocomplete}
				   className={styleInput + " " + (error ? "ring-red-400" : "ring-gray-300")} />
			{type === "password"
				? <div class="absolute inset-y-0 right-0 px-2 cursor-pointer flex items-center" onClick={() => setShowValue(!showValue)}>
					<span className={showValue ? "icon-vision-not" : "icon-vision"}></span>
				</div>
				: null
			}
		</div>
		{error ? <div className="text-red-500 mt-1 text-base xl:text-sm">
			<span className="icon-error inline-block translate-y-0.5" />
			<span className="ml-1">{error}</span>
		</div> : null}
	</>
}
