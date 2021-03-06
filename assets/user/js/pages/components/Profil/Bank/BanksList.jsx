import React, { Component } from 'react';

import Routing        from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { Alert }      from "@dashboardComponents/Tools/Alert";
import { Button }     from "@dashboardComponents/Tools/Button";

import { BanksItem }   from "./BanksItem";

export class BanksList extends Component {
    render () {
        const { isRegistration=false, data, bank, onOpenAside } = this.props;

        return <>
            <div className="toolbar">
                <div className="item create">
                    {!isRegistration ? <Button element="a" onClick={Routing.generate('user_bank_create')}>Ajouter un RIB</Button>
                        : <Button onClick={() => onOpenAside("create")}>Ajouter un RIB</Button>}
                </div>
            </div>
            <div>
                <div className="items-table">
                    <div className="items items-default">
                        <div className="item item-header">
                            {isRegistration && <div className="item-header-selector" />}
                            <div className="item-content">
                                <div className="item-body">
                                    <div className="infos infos-col-3">
                                        <div className="col-1">Titulaire / BIC</div>
                                        <div className="col-2">Iban</div>
                                        <div className="col-3 actions">{!isRegistration && "Actions"}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {data && data.length !== 0 ? data.map(elem => {
                            return <BanksItem {...this.props} bank={bank} elem={elem} key={elem.id}/>
                        }) : <Alert>Aucun résultat</Alert>}
                    </div>
                </div>
            </div>
        </>
    }
}