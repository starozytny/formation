import React, { Component } from 'react';
import PropTypes from 'prop-types';

import axios from "axios";
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Input, InputFile, Radiobox } from "@commonComponents/Elements/Fields";
import { TinyMCE } from "@commonComponents/Elements/TinyMCE";
import { Button } from "@commonComponents/Elements/Button";

import Formulaire from "@commonFunctions/formulaire";
import Validateur from "@commonFunctions/validateur";

const URL_INDEX_ELEMENTS = "manager_news_index";
const URL_CREATE_ELEMENT = "intern_api_fo_news_create";
const URL_UPDATE_GROUP = "intern_api_fo_news_update";
const TEXT_CREATE = "Ajouter l'actualité";
const TEXT_UPDATE = "Enregistrer les modifications";

export function NewsFormulaire ({ context, element }) {
	let url = Routing.generate(URL_CREATE_ELEMENT);

	if (context === "update") {
		url = Routing.generate(URL_UPDATE_GROUP, { 'id': element.id });
	}

	let form = <Form
		context={context}
		url={url}
		name={element ? Formulaire.setValue(element.name) : ""}
		visibility={element ? Formulaire.setValue(element.visibility) : 0}
		content={element ? Formulaire.setValue(element.content) : ""}
		fileFile={element ? Formulaire.setValue(element.fileFile) : null}
	/>

	return <div className="formulaire">{form}</div>;
}

NewsFormulaire.propTypes = {
	context: PropTypes.string.isRequired,
	element: PropTypes.object,
}

class Form extends Component {
	constructor (props) {
		super(props);

		let content = props.content ? props.content : ""

		this.state = {
			name: props.name,
			visibility: props.visibility,
			content: { value: content, html: content },
			errors: [],
		}

		this.file = React.createRef();
	}

	handleChange = (e) => {
		this.setState({ [e.currentTarget.name]: e.currentTarget.value })
	}

	handleChangeTinyMCE = (name, html) => {
		this.setState({ [name]: {value: this.state[name].value, html: html} })
	}

	handleSubmit = (e) => {
		e.preventDefault();

		const { url } = this.props;
		const { name, visibility, content } = this.state;

		this.setState({ errors: [] });

		let paramsToValidate = [
			{ type: "text", id: 'name', value: name },
			{ type: "text", id: 'visibility', value: visibility },
			{ type: "text", id: 'content', value: content },
		];

		let validate = Validateur.validateur(paramsToValidate)
		if (!validate.code) {
			Formulaire.showErrors(this, validate);
		} else {
			let self = this;
			Formulaire.loader(true);

			let formData = new FormData();
			formData.append("data", JSON.stringify(this.state));

			let file = this.file.current;
			if(file.state.files.length > 0){
				formData.append("file", file.state.files[0]);
			}

			axios({ method: "POST", url: url, data: formData, headers: {'Content-Type': 'multipart/form-data'} })
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
		const { context, fileFile } = this.props;
		const { errors, name, visibility, content } = this.state;

		let visibilityItems = [
			{ value: 0, identifiant: 'vis-0', label: 'Tout le monde' },
			{ value: 1, identifiant: 'vis-1', label: 'Utilisateurs' },
		]

		let paramsInput0 = { errors: errors, onChange: this.handleChange }

		return <>
			<form onSubmit={this.handleSubmit}>
				<div className="line-container">
					<div className="line">
						<div className="line-col-1">
							<div className="title">Titre</div>
						</div>
						<div className="line-col-2">
							<div className="line">
								<Input identifiant="name" valeur={name} {...paramsInput0}>Intitulé</Input>
							</div>
							<div className="line line-fat-box">
								<Radiobox items={visibilityItems} identifiant="visibility" valeur={visibility} {...paramsInput0}>
									Visibilité
								</Radiobox>
							</div>
						</div>
					</div>
					<div className="line">
						<div className="line-col-1">
							<div className="title">Contenu</div>
						</div>
						<div className="line-col-2">
							<div className="line">
								<TinyMCE type={99} identifiant='content' valeur={content.value}
										 errors={errors} onUpdateData={this.handleChangeTinyMCE}>
									Contenu de l'actualité
								</TinyMCE>
							</div>

							<div className="line">
								<InputFile ref={this.file} type="simple" identifiant="file" valeur={fileFile}
										   placeholder="Glissez et déposer une illustration" {...paramsInput0}>
									Avatar
								</InputFile>
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
