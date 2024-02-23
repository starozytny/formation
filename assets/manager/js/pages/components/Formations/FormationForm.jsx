import React, { Component } from 'react';
import PropTypes from 'prop-types';
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import axios from "axios";

import { Input, InputCity, InputView, Radiobox, Select } from "@commonComponents/Elements/Fields";
import { TinyMCE } from "@commonComponents/Elements/TinyMCE";
import { Button } from "@commonComponents/Elements/Button";

import Inputs from "@commonFunctions/inputs";
import Formulaire from "@commonFunctions/formulaire";
import Validateur from "@commonFunctions/validateur";

const URL_INDEX_ELEMENTS = "manager_formations_index";
const URL_CREATE_ELEMENT = "intern_api_fo_formations_create";
const URL_UPDATE_GROUP = "intern_api_fo_formations_update";
const TEXT_CREATE = "Ajouter la formation";
const TEXT_UPDATE = "Enregistrer les modifications";

let saveZipcodes = [];

export function FormationFormulaire ({ context, element, taxs }) {
	let url = Routing.generate(URL_CREATE_ELEMENT);

	if (context === "update") {
		url = Routing.generate(URL_UPDATE_GROUP, { 'id': element.id });
	}

	let form = <Form
		context={context}
		url={url}

		taxs={taxs}

		name={element ? Formulaire.setValue(element.name) : ""}
		priceHt={element ? Formulaire.setValue(element.priceHt) : ""}
		tva={element ? Formulaire.setValue(element.tva) : ""}
		nbMin={element ? Formulaire.setValue(element.nbMin) : ""}
		nbMax={element ? Formulaire.setValue(element.nbMax) : ""}
		nbRemain={element ? Formulaire.setValue(element.nbRemain) : ""}
		startAt={element ? Formulaire.setValueDate(element.startAt) : ""}
		endAt={element ? Formulaire.setValueDate(element.endAt) : ""}
		startTimeAm={element ? Formulaire.setValueDate(element.startTimeAm) : ""}
		endTimeAm={element ? Formulaire.setValueDate(element.endTimeAm) : ""}
		startTimePm={element ? Formulaire.setValueDate(element.startTimePm) : ""}
		endTimePm={element ? Formulaire.setValueDate(element.endTimePm) : ""}
		address={element ? Formulaire.setValue(element.address) : ""}
		address2={element ? Formulaire.setValue(element.address2) : ""}
		complement={element ? Formulaire.setValue(element.complement) : ""}
		zipcode={element ? Formulaire.setValue(element.zipcode) : ""}
		city={element ? Formulaire.setValue(element.city) : ""}
		type={element ? Formulaire.setValue(element.type) : 0}
		content={element ? Formulaire.setValue(element.content) : ""}
		target={element ? Formulaire.setValue(element.target) : ""}
		requis={element ? Formulaire.setValue(element.requis) : ""}
	/>

	return <div className="formulaire">{form}</div>;
}

FormationFormulaire.propTypes = {
	context: PropTypes.string.isRequired,
	element: PropTypes.object,
	taxs: PropTypes.array,
}

function calculTtc (priceHt, tva) {
	return priceHt * (tva / 100) + priceHt;
}

class Form extends Component {
	constructor (props) {
		super(props);

		let content = props.content ? props.content : "";
		let target = props.target ? props.target : "";
		let requis = props.requis ? props.requis : "";

		let priceHt = props.priceHt ? parseFloat(props.priceHt) : null;

		this.state = {
			name: props.name,
			priceHt: props.priceHt,
			tva: props.tva,
			priceTtc: priceHt ? calculTtc(priceHt, parseFloat(props.tva)) : "0",
			nbMin: props.nbMin,
			nbMax: props.nbMax,
			nbRemain: props.nbRemain,
			startAt: props.startAt,
			endAt: props.endAt,
			startTimeAm: props.startTimeAm,
			endTimeAm: props.endTimeAm,
			startTimePm: props.startTimePm,
			endTimePm: props.endTimePm,
			address: props.address,
			address2: props.address2,
			complement: props.complement,
			zipcode: props.zipcode,
			city: props.city,
			type: props.type,
			content: { value: content, html: content },
			target: { value: target, html: target },
			requis: { value: requis, html: requis },
			errors: [],
			arrayZipcodes: [],
			openCities: "",
			cities: [],
		}
	}

	componentDidMount = () => {
		Inputs.initDateInput(this.handleChangeDate, this.handleChange, new Date());
		Inputs.getZipcodes(this);
	}

	handleChange = (e, picker) => {
		const { arrayZipcodes } = this.state;

		this.setState({ openCities: "" })

		let name = e.currentTarget.name;
		let value = e.currentTarget.value;

		if(name === "startAt" || name === "endAt"){
			value = Inputs.dateInput(e, picker, this.state[name]);
		}

		if(name === "nbMin" || name === "nbMax" || name === "nbRemain" || name === "zipcode"){
			value = Inputs.textNumericInput(value, this.state[name]);
		}

		if(name === "priceHt" || name === "tva"){
			let priceTtc = 0;
			value = Inputs.textMoneyMinusInput(value, this.state[name]);

			let priceHt = name === "priceHt" ? value : this.state.priceHt;
			let tva = name === "tva" ? value : this.state.tva;

			if(priceHt !== "" && tva !== ""){
				priceTtc = calculTtc(parseFloat(priceHt), parseFloat(tva));
			}

			this.setState({ priceTtc: priceTtc })
		}

		if(name === "startTimeAm" || name === "endTimeAm" || name === "startTimePm" || name === "endTimePm"){
			value = Inputs.timeInput(e, this.state[name]);
		}

		if (name === "zipcode") {
			Inputs.cityInput(this, e, this.state.zipcode, arrayZipcodes ? arrayZipcodes : saveZipcodes)
		}

		this.setState({ [name]: value })
	}

	handleChangeTinyMCE = (name, html) => {
		this.setState({ [name]: {value: this.state[name].value, html: html}, openCities: "" })
	}

	handleChangeDate = (name, value) => { this.setState({ [name]: value, openCities: "" }) }

	handleSelectCity = (name, value) => {
		this.setState({ [name]: value, openCities: "" })
	}

	handleSubmit = (e) => {
		e.preventDefault();

		const { context, url } = this.props;
		const {
			name, priceHt, tva, nbMin, nbMax, nbRemain,
			startAt, endAt, startTimeAm, endTimeAm, startTimePm, endTimePm,
			type, content
		} = this.state;

		this.setState({ errors: [] });

		let paramsToValidate = [
			{ type: "text", id: 'name', value: name },
			{ type: "text", id: 'nbMin', value: nbMin },
			{ type: "text", id: 'nbMax', value: nbMax },
			{ type: "text", id: 'nbRemain', value: nbRemain },
			{ type: "text", id: 'priceHt', value: priceHt },
			{ type: "text", id: 'tva', value: tva },
			{ type: "date", id: 'startAt', value: startAt },
			{ type: "date", id: 'endAt', value: endAt },
			{ type: "text", id: 'type', value: type },
			{ type: "text", id: 'content', value: content },
		];

		if(startTimeAm !== "" || endTimeAm !== ""){
			paramsToValidate = [...paramsToValidate, ...[
				{type: "time", id: 'startTimeAm', value: startTimeAm},
				{type: "time", id: 'endTimeAm', value: endTimeAm},
			]];
		}

		if(startTimePm !== "" || endTimePm !== ""){
			paramsToValidate = [...paramsToValidate, ...[
				{type: "time", id: 'startTimePm', value: startTimePm},
				{type: "time", id: 'endTimePm', value: endTimePm},
			]];
		}

		let validate = Validateur.validateur(paramsToValidate)
		if (!validate.code) {
			Formulaire.showErrors(this, validate);
		} else {
			let self = this;
			Formulaire.loader(true);

			saveZipcodes = this.state.arrayZipcodes;
			delete this.state.arrayZipcodes;

			axios({ method: context === "update" ? "PUT" : "POST", url: url, data: this.state })
				.then(function (response) {
					location.href = Routing.generate(URL_INDEX_ELEMENTS, { 'h': response.data.id });
				})
				.catch(function (error) {
					Formulaire.displayErrors(self, error);
					Formulaire.loader(false);
				})
			;
		}
	}

	render () {
		const { context, taxs } = this.props;
		const {
			errors, name, priceHt, tva, priceTtc, nbMin, nbMax, nbRemain,
			startAt, endAt, startTimeAm, endTimeAm, startTimePm, endTimePm,
			address, address2, complement, zipcode, city, cities, openCities,
			type, content, target, requis
		} = this.state;

		let typeItems = [
			{ value: 0, identifiant: 'type-0', label: 'Présentiel' },
			{ value: 1, identifiant: 'type-1', label: 'En ligne' },
		]

		let taxItems = [];
		taxs.forEach(t => {
			taxItems.push({ value: t.taux, identifiant: 'tax-' + t.taux, label: t.taux + "%" })
		})

		let paramsInput0 = { errors: errors, onChange: this.handleChange }
		let paramsInput1 = { errors: errors, onUpdateData: this.handleChangeTinyMCE }

		return <>
			<form onSubmit={this.handleSubmit}>
				<div className="line-container">
					<div className="line">
						<div className="line-col-1">
							<div className="title">Informations générales</div>
						</div>
						<div className="line-col-2">
							<div className="line">
								<Input identifiant="name" valeur={name} {...paramsInput0}>Intitulé *</Input>
							</div>
							<div className="line line-3">
								<Input identifiant="nbMin" valeur={nbMin} {...paramsInput0}>Places min *</Input>
								<Input identifiant="nbMax" valeur={nbMax} {...paramsInput0}>Places max *</Input>
								<Input identifiant="nbRemain" valeur={nbRemain} {...paramsInput0}>Places restantes *</Input>
							</div>
							<div className="line">
								<Input identifiant="address" valeur={address} {...paramsInput0}>Adresse</Input>
							</div>
							<div className="line line-2">
								<Input identifiant="address2" valeur={address2} {...paramsInput0}>Adresse suite</Input>
								<Input identifiant="complement" valeur={complement} {...paramsInput0}>Complément</Input>
							</div>
							<div className="line line-2">
								<Input identifiant="zipcode" valeur={zipcode} {...paramsInput0}>Code postal</Input>
								<InputCity identifiant="city" valeur={city} {...paramsInput0}
										   cities={cities} openCities={openCities} onSelectCity={this.handleSelectCity}>
									Ville
								</InputCity>
							</div>
						</div>
					</div>
					<div className="line">
						<div className="line-col-1">
							<div className="title">Dates et horaires</div>
						</div>
						<div className="line-col-2">
							<div className="line line-2">
								<Input type="js-date" identifiant="startAt" valeur={startAt} {...paramsInput0}>Début *</Input>
								<Input type="js-date" identifiant="endAt" valeur={endAt} {...paramsInput0}>Fin *</Input>
							</div>
							<div className="line line-4">
								<Input placeholder="00h00" identifiant="startTimeAm" valeur={startTimeAm} {...paramsInput0}>Début horaire du matin</Input>
								<Input placeholder="00h00" identifiant="endTimeAm" valeur={endTimeAm} {...paramsInput0}>Fin horaire du matin</Input>
								<Input placeholder="00h00" identifiant="startTimePm" valeur={startTimePm} {...paramsInput0}>Début horaire de l'après-midi</Input>
								<Input placeholder="00h00" identifiant="endTimePm" valeur={endTimePm} {...paramsInput0}>Fin horaire de l'après-midi</Input>
							</div>
						</div>
					</div>
					<div className="line">
						<div className="line-col-1">
							<div className="title">Financier</div>
						</div>
						<div className="line-col-2">
							<div className="line line-3">
								<Input identifiant="priceHt" valeur={priceHt} {...paramsInput0}>Prix HT *</Input>
								<Select items={taxItems} identifiant="tva" valeur={tva} {...paramsInput0}>TVA *</Select>
								<InputView identifiant="priceTtc" valeur={priceTtc} {...paramsInput0}>Prix TTC</InputView>
							</div>
						</div>
					</div>
					<div className="line">
						<div className="line-col-1">
							<div className="title">Contenu</div>
						</div>
						<div className="line-col-2">
							<div className="line line-fat-box">
								<Radiobox items={typeItems} identifiant="type" valeur={type} {...paramsInput0}>
									Type de formation *
								</Radiobox>
							</div>
							<div className="line">
								<TinyMCE type={3} identifiant='content' valeur={content.value} {...paramsInput1}>
									Contenu de la formation *
								</TinyMCE>
							</div>
							<div className="line">
								<TinyMCE type={3} identifiant='target' valeur={target.value} {...paramsInput1}>
									Public ciblé
								</TinyMCE>
							</div>
							<div className="line">
								<TinyMCE type={3} identifiant='requis' valeur={requis.value} {...paramsInput1}>
									Pré-requis
								</TinyMCE>
							</div>
						</div>
					</div>
				</div>

				<div className="line-buttons">
					<Button isSubmit={true} type="primary">{context === "create" ? TEXT_CREATE : TEXT_UPDATE}</Button>
				</div>
			</form>
		</>
	}
}
