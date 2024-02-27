import React, { useState } from "react";
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import axios from "axios";
import toastr from "toastr";

import Sanitaze from '@commonFunctions/sanitaze';
import Validateur from "@commonFunctions/validateur";
import Formulaire from "@commonFunctions/formulaire";

import { Button, ButtonA } from "@userComponents/Elements/Button";
import { Alert } from "@userComponents/Elements/Alert";

const URL_TEAM_PAGE = "user_workers_index";
const URL_CREATE_ELEMENT = "intern_api_fo_orders_create";

export function Preregistration ({ formation, workers })
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
			let nParticipants = [...participants, ...[element]];
			if(nParticipants.length <= formation.nbRemain){
				setParticipants(nParticipants)
			}else{
				toastr.error("Nombre de places maximum atteint.")
			}
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
			let paramsToValidate = [
				{ type: "array", id: 'step2', value: participants },
			];

			let validate = Validateur.validateur(paramsToValidate)
			if (!validate.code) {
				setErrors(validate.errors)
			} else {
				Formulaire.loader(true);

				let data = {
					participants: participants,
					formationId: formation.id
				}

				axios({ method: "POST", url: Routing.generate(URL_CREATE_ELEMENT), data: data })
					.then(function (response) {
						setProgress('full');
						setStep(value);
					})
					.catch(function (error) {
						Formulaire.displayErrors(null, error);
					})
					.then(function () {
						Formulaire.loader(false);
					})
				;
			}
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
								  formation={formation} data={workers}
								  participants={participants}
								  onClick={handleClickWorker}  />}
			{step === 2 && <Step2 errors={errors} onStep={handleStep}
								  formation={formation}
								  participants={participants} />}
			{step === 3 && <Step3 />}
		</div>
	</>
}

function Step1 ({ errors, onStep, formation, data, participants, onClick })
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
			<div className="mt-4">
				<Alert color="blue" title="Places restantes">
					Il reste <u>{formation.nbRemain} place{formation.nbRemain > 1 ? "s" :""}</u>.
					Attention, ce nombre peut varié jusqu'à la validation de la préinscription !
				</Alert>
			</div>
		</div>
		<div className="mt-6">
			<div className="grid gap-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
				{data.length === 0
					? <>
						<p>Rendez-vous dans la rubrique Équipe pour ajouter des potentiels participants à la formation.</p>
						<div>
							<ButtonA type="blue" link={Routing.generate(URL_TEAM_PAGE)}>Mon équipe</ButtonA>
						</div>
					</>
					: (data.map(elem => {

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
					}))}
			</div>
		</div>

		{data.length > 0
			? <>
				<div className={error ? 'mt-4' : 'hidden'}>
					<Alert icon="warning" color="red" title="Erreur concernant le nombre de participant.">{error}</Alert>
				</div>

				<div className="mt-4">
					<Button type="blue" width="w-full" onClick={() => onStep(2)}>
						Suivant
					</Button>
				</div>
			</>
			: null}
	</div>
}

function Step2 ({ errors, onStep, formation, participants })
{
	let error;
	errors.forEach(err => {
		if(err.name === "step2"){
			error = err.message;
		}
	})

	let nbP = participants.length;
	let priceTtc = formation.priceHt * (formation.tva / 100) + formation.priceHt;
	let total = nbP * priceTtc;
	total = Math.round((total + Number.EPSILON) * 100) / 100

	return <div className="bg-white rounded-md shadow p-4">
		<div className="leading-4">
			<h2 className="text-lg">Récapitulatif</h2>
			<p className="text-gray-600">
				Vérifiez vos informations puis validez la préinscription.
			</p>
		</div>
		<div className="mt-6 grid gap-6 sm:grid-cols-3">
			<div className="sm:col-span-2">
				<div>
					<h3 className="font-semibold mb-1 text-gray-700">Formation</h3>
					<div>
						<div className="font-medium">{formation.name}</div>
						<div><span className="text-gray-600">Prix HT</span> : <span className="font-medium">{Sanitaze.toFormatCurrency(formation.priceHt)} / personne</span></div>
						<div className="mb-2"><span className="text-gray-600">Prix TTC</span> : <span className="font-medium">{Sanitaze.toFormatCurrency(priceTtc)} / personne</span></div>
						<div><span className="text-gray-600">Type</span> : {formation.typeString}</div>
						<div><span className="text-gray-600">Début</span> : {Sanitaze.toDateFormat(formation.startAt, 'L')}</div>
						<div><span className="text-gray-600">Fin</span> : {Sanitaze.toDateFormat(formation.endAt, 'L')}</div>
						{formation.startTimeAm
							? <div>
								<span className="text-gray-600">Horaires du matin</span> : {Sanitaze.toDateFormat(formation.startTimeAm, 'LT', '', true)} à {Sanitaze.toDateFormat(formation.endTimeAm, 'LT', '', true)}
							</div>
							: null
						}
						{formation.startTimePm
							? <div>
								<span className="text-gray-600">Horaires de l'après midi</span> : {Sanitaze.toDateFormat(formation.startTimePm, 'LT', '', true)} à {Sanitaze.toDateFormat(formation.endTimePm, 'LT', '', true)}
							</div>
							: null
						}
						{formation.address
							? <div className="mt-2">
								<span className="text-gray-600">Adresse</span> : {formation.address} {formation.address2} {formation.complement} {formation.zipcode}, {formation.city}
							</div>
							: null
						}
					</div>
				</div>
				<div className="mt-4">
					<h3 className="font-semibold mb-2 text-gray-700">Participants</h3>
					<div>
						<div className="flex flex-col gap-2">
							{participants.map(el => {
								return <div key={el.id} className="w-full leading-4 rounded-md p-2 shadow border border-blue-300 flex justify-between max-w-md">
									<div>
										<div className="font-medium">{el.lastname} {el.firstnmae}</div>
										<div className="text-gray-600">{el.email}</div>
									</div>
									<div className="text-lg">{Sanitaze.toFormatCurrency(priceTtc)}</div>
								</div>
							})}
						</div>
					</div>
				</div>
			</div>
			<div className="w-full border-t pt-4 sm:border-t-0 sm:border-l sm:pt-0 sm:pl-4">
				<div>
					<h3 className="font-semibold mb-1 text-gray-700">Préinscription</h3>
					<div>
						<div>Pour {nbP} participant{nbP > 1 ? 's' : ''}</div>
						<div className="text-lg mt-4">Total : <span className="font-bold text-blue-700">{Sanitaze.toFormatCurrency(total)}</span></div>
					</div>
				</div>
			</div>
		</div>

		<div className={error ? 'mt-4' : 'hidden'}>
			<Alert icon="warning" color="red" title="Erreur à la validation.">{error}</Alert>
		</div>

		<div className="mt-6 flex flex-row gap-2">
			<Button type="default" width="w-full" iconLeft="left-arrow" onClick={() => onStep(1)}>
				Précèdent
			</Button>
			<Button type="blue" width="w-full" iconRight="right-arrow" onClick={() => onStep(3)}>
				Valider ({Sanitaze.toFormatCurrency(total)})
			</Button>
		</div>
	</div>
}

function Step3 ({}) {
	return <div className="bg-white rounded-md shadow p-4">
		<div className="mt-6 flex flex-row gap-2">
			<Button type="blue" width="w-full" iconRight="right-arrow">
				Voir mes inscriptions
			</Button>
		</div>
	</div>
}
