import React, { useState } from "react";

export function Preregistration ({ workers })
{
	const [step, setStep] = useState(1);
	const [participants, setParticipants] = useState([]);

	let handleClickWorker = (element) => {
		let find = false;
		participants.forEach(el => {
			if(el.id === element.id){
				find = true;
			}
		})

		if(!find){
			setParticipants([...participants, ...[element]])
		}else{
			setParticipants(participants.filter(el => el.id !== element.id))
		}
	}

	let progress = '1/12';
	if(step === 2) progress = '1/2';
	if(step === 3) progress = 'full';

	return <>
		<div className="relative bg-white rounded-md shadow p-4 flex flex-col-reverse max-w-screen-md sm:flex-col sm:p-6">
			<div className="relative block w-full h-2 bg-slate-200 rounded-full">
				<div className={`absolute top-0 left-0 block w-${progress} h-2 bg-blue-600 rounded-full`}></div>
			</div>
			<div className="w-full flex justify-center gap-4 leading-5 font-medium mb-2 sm:mb-0 sm:mt-3 sm:justify-between">
				<div className={`${step === 1 ? "block" : "hidden"} text-center sm:block sm:text-left`}>
					<div className={`${step >= 1 ? "text-blue-600" : "text-gray-500"}`}>Étape 1</div>
					<div>Participants</div>
				</div>
				<div className={`${step === 2 ? "block" : "hidden"} text-center sm:block sm:text-left`}>
					<div className={`${step >= 2 ? "text-blue-600" : "text-gray-500"}`}>Étape 2</div>
					<div>Récapitulatif</div>
				</div>
				<div className={`${step === 3 ? "block" : "hidden"} text-center sm:block sm:text-left`}>
					<div className={`${step >= 3 ? "text-blue-600" : "text-gray-500"}`}>Étape 3</div>
					<div>Préinscription</div>
				</div>
			</div>
		</div>

		<div className="mt-4">
			<Step1 data={JSON.parse(workers)} participants={participants} onClick={handleClickWorker} />
		</div>
	</>
}

function Step1 ({ data, participants, onClick })
{
	return <div className="bg-white rounded-md shadow p-4">
		<div className="leading-4">
			<h2 className="text-lg">Sélection des participants</h2>
			<p className="text-gray-600">
				Cliquez sur un participant pour le sélectionner.
				Cliquez sur un participant sélectionné pour le désélectionner.
			</p>
		</div>
		<div className="mt-6">
			<div className="grid gap-2 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
				{data.map(elem => {

					let active = "hover:border-blue-300";
					participants.forEach(p => {
						if(p.id === elem.id){
							active = "border-blue-600"
						}
					})

					return <div key={elem.id} onClick={() => onClick(elem)}
								className={`cursor-pointer leading-4 shadow border-2 rounded-md p-2 ${active}`}
					>
						<div className="font-medium">{elem.lastname} {elem.firstnmae}</div>
						<div className="text-gray-600">{elem.email}</div>
					</div>
				})}
			</div>
		</div>
	</div>
}
