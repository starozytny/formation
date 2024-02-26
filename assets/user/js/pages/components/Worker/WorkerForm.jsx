import React, { Component } from 'react';

import axios from "axios";
import toastr from "toastr";
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Formulaire from "@commonFunctions/formulaire";
import Validateur from "@commonFunctions/validateur";

import { Input } from "@userComponents/Elements/Fields";
import { ButtonACancel, ButtonSubmit } from "@userComponents/Elements/Button";

const URL_INDEX_ELEMENT = "user_workers_index";
const URL_CREATE_ELEMENT = "intern_api_fo_workers_create";
const URL_UPDATE_ELEMENT = "intern_api_fo_workers_update";

export class WorkerFormulaire extends Component {
	constructor (props) {
		super(props);

		let element = props.element;

		this.state = {
			email: element ? Formulaire.setValue(element.email) : "",
			lastname: element ? Formulaire.setValue(element.lastname) : "",
			firstname: element ? Formulaire.setValue(element.firstname) : "",
			type: element ? Formulaire.setValue(element.type) : 0,
			errors: [],
		}
	}

	handleChange = (e) => {
		let name = e.currentTarget.name;
		let value = e.currentTarget.value;

		this.setState({ [name]: value })
	}

	handleSubmit = (e) => {
		e.preventDefault();

		const { context, element } = this.props;
		const { email, lastname, firstname } = this.state;

		this.setState({ errors: [] });

		let paramsToValidate = [
			{ type: "email", id: 'email', value: email },
			{ type: "text", id: 'lastname', value: lastname },
			{ type: "text", id: 'firstname', value: firstname },
		];

		let validate = Validateur.validateur(paramsToValidate)
		if (!validate.code) {
			Formulaire.showErrors(this, validate);
		} else {
			Formulaire.loader(true);
			let self = this;

			let method = "POST", url = Routing.generate(URL_CREATE_ELEMENT)
			if(context === "update"){
				method = "PUT";
				url = Routing.generate(URL_UPDATE_ELEMENT, {id: element.id});
			}

			axios({ method: method, url: url, data: this.state })
				.then(function (response) {
					toastr.info(response.data.message);
					location.href = Routing.generate(URL_INDEX_ELEMENT);
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
		const { errors, email, lastname, firstname } = this.state;

		let params = { errors: errors, onChange: this.handleChange }

		return <>
			<form onSubmit={this.handleSubmit}>
				<div>
					<div className="bg-white rounded-md shadow p-4">
						<div className="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
							<div className="w-full">
								<Input valeur={lastname} identifiant="lastname" disabled={context === "update"} {...params}>Nom</Input>
							</div>
							<div className="w-full">
								<Input valeur={firstname} identifiant="firstname" disabled={context === "update"} {...params}>Prénom</Input>
							</div>
							<div className="w-full col-span-2 md:col-span-1">
								<Input valeur={email} identifiant="email" {...params} type="email">E-mail</Input>
							</div>
						</div>

						<div className="mb-4">
						</div>

						<div className="flex flex-row justify-end gap-2 mt-4 pt-4 border-t">
							<ButtonACancel link={Routing.generate(URL_INDEX_ELEMENT)} />
							<ButtonSubmit>{context === "create" ? "Ajouter le membre" : "Mettre à jour"}</ButtonSubmit>
						</div>
					</div>
				</div>
			</form>
		</>
	}
}
