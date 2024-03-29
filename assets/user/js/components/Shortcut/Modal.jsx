import React, { Component } from "react";
import PropTypes from "prop-types";

import axios from "axios";
import toastr from "toastr";
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Formulaire from "@commonFunctions/formulaire";

import { Button } from "@userComponents/Elements/Button";
import { Modal } from "@userComponents/Elements/Modal";

export class ModalDelete extends Component {
	constructor (props) {
		super(props);

		this.handleDelete = this.handleDelete.bind(this);
	}

	handleDelete = () => {
		const { refModal, element, routeName, msgSuccess, onUpdateList } = this.props;

		let self = this;
		axios({ method: "DELETE", url: Routing.generate(routeName, { 'id': element.id }), data: {} })
			.then(function (response) {
				toastr.info(msgSuccess);
				refModal.current.handleClose();
				onUpdateList(element, "delete");
			})
			.catch(function (error) {
				Formulaire.displayErrors(self, error);
			})
		;
	}

	render () {
		const { refModal, title, children, identifiant = "delete", maxWidth = 414 } = this.props;

		return <Modal ref={refModal} identifiant={identifiant} maxWidth={maxWidth} title={title}
					  content={<p>{children}</p>}
					  footer={<Button type="red" onClick={this.handleDelete}>
						  Confirmer la suppression
					  </Button>}
		/>
	}

}

ModalDelete.propTypes = {
	refModal: PropTypes.object.isRequired,
	title: PropTypes.string.isRequired,
	children: PropTypes.string.isRequired,
	routeName: PropTypes.string.isRequired,
	msgSuccess: PropTypes.string.isRequired,
	element: PropTypes.object,
	onUpdateList: PropTypes.func.isRequired,
	identifiant: PropTypes.string,
	maxWidth: PropTypes.number,
}
