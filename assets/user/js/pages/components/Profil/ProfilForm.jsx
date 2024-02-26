import React, { Component } from 'react';

import axios from "axios";
import toastr from "toastr";
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Formulaire from "@commonFunctions/formulaire";
import Validateur from "@commonFunctions/validateur";

import { Input } from "@userComponents/Elements/Fields";

const URL_INDEX_ELEMENT = "user_profil_index";
const URL_UPDATE_ELEMENT = "user_profil_update";

export class ProfilFormulaire extends Component {
	constructor (props) {
		super(props);

		let element = props.element;

		this.state = {
			email: element ? Formulaire.setValue(element.email) : "",
			lastname: element ? Formulaire.setValue(element.lastname) : "",
			firstname: element ? Formulaire.setValue(element.firstname) : "",
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

		const { email, lastname, firstname } = this.state;

		this.setState({ errors: [] });

		let paramsToValidate = [
			{ type: "email", id: 'email', value: email },
			{ type: "text", id: 'lastname', value: lastname },
			{ type: "text", id: 'firstname', value: firstname },
		];

		// validate global
		let validate = Validateur.validateur(paramsToValidate)
		if (!validate.code) {
			Formulaire.showErrors(this, validate);
		} else {
			Formulaire.loader(true);
			let self = this;

			axios({ method: "PUT", url: Routing.generate(URL_UPDATE_ELEMENT), data: this.state })
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
		const { errors, email, lastname, firstname } = this.state;

		let params = { errors: errors, onChange: this.handleChange }

		return <>
			<form onSubmit={this.handleSubmit}>

				<div className="grid sm:grid-cols-2 gap-8">
					<div>
						<div className="font-semibold xl:text-lg">Profil</div>
						<div className="text-gray-600">
							Ces informations seront utilisées pour les inscriptions et documents.
						</div>
					</div>
					<div>
						<div className="bg-white rounded-md shadow p-6 xl:p-4">
							<div className="mb-4">
								<div>
									<Input valeur={email} identifiant="email" {...params} type="email">E-mail</Input>
								</div>
							</div>

							<div className="flex flex-row gap-4">
								<div className="w-full">
									<Input valeur={lastname} identifiant="lastname" {...params}>Nom</Input>
								</div>
								<div className="w-full">
									<Input valeur={firstname} identifiant="firstname" {...params}>Prénom</Input>
								</div>
							</div>

							<div className="flex flex-row justify-end gap-2 mt-4 pt-4 border-t">
								<a href={Routing.generate(URL_INDEX_ELEMENT)}
								   className="inline-block rounded-md font-semibold py-2 px-4 text-center transition-colors hover:bg-gray-100">
									Annuler
								</a>
								<button type="submit" className="inline-block rounded-md bg-blue-600 font-semibold py-2 px-4 text-center text-slate-50 transition-colors hover:bg-blue-500">
									Mettre à jour
								</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</>
	}
}
