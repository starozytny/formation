import React, { Component } from 'react';
import PropTypes from 'prop-types';

import axios from "axios";
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Input, InputView } from "@commonComponents/Elements/Fields";
import { Button } from "@commonComponents/Elements/Button";

import Inputs from "@commonFunctions/inputs";
import Formulaire from "@commonFunctions/formulaire";
import Validateur from "@commonFunctions/validateur";

const URL_INDEX_ELEMENTS = "manager_settings_update";
const URL_CREATE_ELEMENT = "intern_api_fo_settings_taxs_create";
const URL_UPDATE_GROUP = "intern_api_fo_settings_taxs_update";
const TEXT_CREATE = "Ajouter la taxe";
const TEXT_UPDATE = "Enregistrer les modifications";

export function TaxFormulaire ({ context, element }) {
	let url = Routing.generate(URL_CREATE_ELEMENT);

	if (context === "update") {
		url = Routing.generate(URL_UPDATE_GROUP, { 'id': element.id });
	}

	let form = <Form
		context={context}
		url={url}
		code={element ? Formulaire.setValue(element.code) : ""}
		taux={element ? Formulaire.setValue(element.taux) : ""}
		numeroCompta={element ? Formulaire.setValue(element.numeroCompta) : ""}
	/>

	return <div className="formulaire">{form}</div>;
}

TaxFormulaire.propTypes = {
	context: PropTypes.string.isRequired,
	element: PropTypes.object,
}

class Form extends Component {
	constructor (props) {
		super(props);

		this.state = {
			code: props.code,
			taux: props.taux,
			numeroCompta: props.numeroCompta,
			errors: [],
		}
	}

	handleChange = (e) => {
		let name = e.currentTarget.name;
		let value = e.currentTarget.value;

		if(name === "code"){
			value = Inputs.textNumericInput(value, this.state[name]);
		}

		if(name === "taux"){
			value = Inputs.textMoneyMinusInput(value, this.state[name]);
		}

		this.setState({ [name]: value })
	}

	handleSubmit = (e) => {
		e.preventDefault();

		const { context, url } = this.props;
		const { code, taux } = this.state;

		this.setState({ errors: [] });

		let paramsToValidate = [
			{ type: "text", id: 'code', value: code },
			{ type: "text", id: 'taux', value: taux },
		];

		let validate = Validateur.validateur(paramsToValidate)
		if (!validate.code) {
			Formulaire.showErrors(this, validate);
		} else {
			let self = this;
			Formulaire.loader(true);

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
		const { context } = this.props;
		const { errors, code, taux, numeroCompta } = this.state;

		let paramsInput0 = { errors: errors, onChange: this.handleChange }

		return <>
			<form onSubmit={this.handleSubmit}>
				<div className="line-container">
					<div className="line">
						<div className="line-col-1">
							<div className="title">Informations</div>
						</div>
						<div className="line-col-2">
							<div className="line">
							</div>
							<div className="line line-3">
								{context === "update"
									? <>
										<InputView identifiant="code" valeur={code} {...paramsInput0}>Code</InputView>
										<InputView identifiant="taux" valeur={taux} {...paramsInput0}>Taux (%)</InputView>
									</>
									: <>
										<Input identifiant="code" valeur={code} {...paramsInput0}>Code</Input>
										<Input identifiant="taux" valeur={taux} {...paramsInput0}>Taux (%)</Input>
									</>
								}
								<Input identifiant="numeroCompta" valeur={numeroCompta} {...paramsInput0}>Numéro de comptabilité</Input>
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
