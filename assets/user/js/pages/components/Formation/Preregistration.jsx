import React, { useState } from "react";
import { Button } from "@userComponents/Elements/Button";
import { Alert } from "@userComponents/Elements/Alert";

export function Preregistration ({ workers })
{
	const [errors, setErrors] = useState([]);
	const [step, setStep] = useState(1);
	const [progress, setProgress] = useState('1/12');
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

	let handleStep = (value) => {
		setErrors([]);

		if(value === 1){
			setProgress('1/12');
			setStep(value);
		}else if(value === 2){
			if(participants.length <= 0){
				setErrors([{
					name: 'step1',
					message: 'Veuillez sélectionner au moins 1 participants pour passer à l\'étape suivante.'
				}])
			}else{
				setProgress('1/2');
				setStep(value);
			}
		}else if(value === 3){
			setProgress('full');
			setStep(value);
		}
	}

	return <>
		<div className="w-1/12"></div><div className="w-1/2"></div><div className="w-full"></div>
		<div className="relative bg-white rounded-md shadow p-4 flex flex-col-reverse max-w-screen-md sm:flex-col sm:p-6">
			<div className="relative block w-full h-2 bg-slate-200 rounded-full">
				<div className={`absolute top-0 left-0 block w-${progress} h-2 bg-blue-600 rounded-full transition-all`}></div>
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
			{step === 1 && <Step1 errors={errors} onStep={handleStep}
								  data={JSON.parse(workers)} participants={participants}
								  onClick={handleClickWorker}  />}
			{step === 2 && <Step2 errors={errors} onStep={handleStep} />}
		</div>
	</>
}

function Step1 ({ data, participants, errors, onClick, onStep })
{
	let error;
	errors.forEach(err => {
		if(err.name === "step1"){
			error = err.message;
		}
	})

	return <div className="bg-white rounded-md shadow p-4">
		<div className="leading-4">
			<h2 className="text-lg">Sélection des participants</h2>
			<p className="text-gray-600">
				Cliquez sur un participant pour le sélectionner.
				Cliquez sur un participant sélectionné pour le désélectionner.
			</p>
		</div>
		<div className="mt-6">
			<div className="grid gap-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
				{data.map(elem => {

					let cardActive = "border-white";
					let survol = "hover:border-blue-300";
					participants.forEach(p => {
						if(p.id === elem.id){
							cardActive = "border-blue-600";
							survol = "";
						}
					})

					return <div key={elem.id} onClick={() => onClick(elem)}
								className={`cursor-pointer leading-4 rounded-md border-2 p-1 ${cardActive}`}
					>
						<div className={`p-2 shadow border rounded ${survol}`}>
							<div className="font-medium">{elem.lastname} {elem.firstnmae}</div>
							<div className="text-gray-600">{elem.email}</div>
						</div>
					</div>
				})}
			</div>
		</div>

		<div className={error ? 'mt-4' : 'hidden'}>
			<Alert icon="warning" color="red" title="Erreur concernant le nombre de participant.">{error}</Alert>
		</div>

		<div className="mt-4">
			<Button type="blue" width="w-full" onClick={() => onStep(2)}>
				Suivant
			</Button>
		</div>
	</div>
}

function Step2 ({ errors, onStep })
{
	let error;
	errors.forEach(err => {
		if(err.name === "step2"){
			error = err.message;
		}
	})

	return <div className="bg-white rounded-md shadow p-4">
		<div className="leading-4">
			<h2 className="text-lg">Récapitulatif</h2>
			<p className="text-gray-600">
				Vérifiez vos informations puis validez la préinscription.
			</p>
		</div>
		<div className="mt-6">
			<div className="grid gap-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
				hellow world
			</div>
		</div>

		<div className={error ? 'mt-4' : 'hidden'}>
			<Alert icon="warning" color="red" title="Erreur concernant le nombre de participant.">{error}</Alert>
		</div>

		<div className="mt-4 flex flex-row gap-2">
			<Button type="default" width="w-full" iconLeft="left-arrow" onClick={() => onStep(1)}>
				Précèdent
			</Button>
			<Button type="blue" width="w-full" iconRight="right-arrow" onClick={() => onStep(3)}>
				Suivant
			</Button>
		</div>
	</div>
}
