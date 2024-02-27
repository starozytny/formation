import React from "react";

export function Preregistration () {
	return <>
		<div className="relative bg-white rounded-md shadow p-4 flex flex-col-reverse mx-auto max-w-screen-md sm:flex-col sm:p-6">
			<div className="relative block w-full h-2 bg-slate-200 rounded-full">
				<div className="relative block w-20 h-2 bg-blue-500 rounded-full"></div>
			</div>
			<div className="w-full flex justify-center gap-4 leading-5 font-medium mb-2 sm:mb-0 sm:mt-3 sm:justify-between">
				<div className="hidden sm:block">
					<div className="text-gray-500">Étape 1</div>
					<div>Participants</div>
				</div>
				<div className="block text-center sm:text-left">
					<div className="text-blue-600">Étape 2</div>
					<div>Récapitulatif</div>
				</div>
				<div className="hidden sm:block">
					<div className="text-gray-500">Étape 3</div>
					<div>Préinscription</div>
				</div>
			</div>
		</div>
	</>
}
