import React, { Component } from 'react';

import axios                   from "axios";
import Routing                 from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Input }               from "@dashboardComponents/Tools/Fields";
import { Alert }               from "@dashboardComponents/Tools/Alert";
import { Button }              from "@dashboardComponents/Tools/Button";
import { FormLayout }          from "@dashboardComponents/Layout/Elements";

import Validateur              from "@commonComponents/functions/validateur";
import Helper                  from "@commonComponents/functions/helper";
import Formulaire              from "@dashboardComponents/functions/Formulaire";

const URL_CREATE_ELEMENT     = "api_bill_taxes_create";
const URL_UPDATE_GROUP       = "api_bill_taxes_update";
const TXT_CREATE_BUTTON_FORM = "Enregistrer";
const TXT_UPDATE_BUTTON_FORM = "Enregistrer les modifications";

export function TaxeFormulaire ({ type, onChangeContext, onUpdateList, element, societyId })
{
    let title = "Ajouter une taxe";
    let url = Routing.generate(URL_CREATE_ELEMENT);
    let msg = "Félicitations ! Vous avez ajouté une nouvelle taxe !"

    if(type === "update"){
        title = "Modifier le code #" + element.code;
        url = Routing.generate(URL_UPDATE_GROUP, {'id': element.id});
        msg = "Félicitations ! La mise à jour s'est réalisée avec succès !";
    }

    let form = <Form
        context={type}
        url={url}

        societyId={societyId}

        isNatif={element ? element.isNatif : false}
        code={element ? Formulaire.setValueEmptyIfNull(element.code) : ""}
        rate={element ? Formulaire.setValueEmptyIfNull(element.rate) : ""}
        numeroComptable={element ? Formulaire.setValueEmptyIfNull(element.numeroComptable) : ""}

        onUpdateList={onUpdateList}
        onChangeContext={onChangeContext}
        messageSuccess={msg}
    />

    return <FormLayout onChangeContext={onChangeContext} form={form}>{title}</FormLayout>
}

class Form extends Component {
    constructor(props) {
        super(props);

        this.state = {
            societyId: props.societyId,
            code: props.code,
            rate: props.rate,
            numeroComptable: props.numeroComptable,
            errors: [],
            success: false
        }

        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange = (e) => { this.setState({[e.currentTarget.name]: e.currentTarget.value}) }

    handleSubmit = (e) => {
        e.preventDefault();

        const { context, url, messageSuccess } = this.props;
        const { code, rate } = this.state;

        let method = context === "create" ? "POST" : "PUT";

        this.setState({ errors: [], success: false })

        let paramsToValidate = [
            {type: "text", id: 'code', value: code},
            {type: "text", id: 'rate', value: rate}
        ];

        // validate global
        let validate = Validateur.validateur(paramsToValidate)
        if(!validate.code){
            Formulaire.showErrors(this, validate);
        }else{
            Formulaire.loader(true);
            let self = this;

            axios({ method: method, url: url, data: this.state })
                .then(function (response) {
                    let data = response.data;
                    Helper.toTop();
                    if(self.props.onUpdateList){
                        self.props.onUpdateList(data);
                    }
                    self.setState({ success: messageSuccess, errors: [] });
                    if(context === "create"){
                        self.setState( {
                            code: "",
                            rate: ""
                        })
                    }
                })
                .catch(function (error) {
                    Formulaire.displayErrors(self, error);
                })
                .then(() => {
                    Formulaire.loader(false);
                })
            ;
        }
    }

    render () {
        const { context, isNatif } = this.props;
        const { errors, success, code, rate, numeroComptable } = this.state;

        return <>
            <form onSubmit={this.handleSubmit}>

                {success !== false && <Alert type="info">{success}</Alert>}

                <div className="line line-3">
                    {!isNatif ? <>
                        <Input valeur={code} identifiant="code" errors={errors} onChange={this.handleChange} type="number">Code</Input>
                        <Input valeur={rate} identifiant="rate" errors={errors} onChange={this.handleChange} type="number" step="any">Taux en %</Input>
                    </> : <>
                        <div className="form-group">
                            <label>Code</label>
                            <div>{code}</div>
                        </div>
                        <div className="form-group">
                            <label>Taux en %</label>
                            <div>{rate}</div>
                        </div>
                    </>}
                    <Input valeur={numeroComptable} identifiant="numeroComptable" errors={errors} onChange={this.handleChange}>Numéro comptable</Input>
                </div>

                <div className="line">
                    <div className="form-button">
                        <Button isSubmit={true}>{context === "create" ? TXT_CREATE_BUTTON_FORM : TXT_UPDATE_BUTTON_FORM}</Button>
                    </div>
                </div>
            </form>
        </>
    }
}
