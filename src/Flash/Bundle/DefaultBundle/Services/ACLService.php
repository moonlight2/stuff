<?php

namespace Flash\Bundle\DefaultBundle\Services;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Acl\Dbal\AclProvider;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;

class ACLService {

    private $context;
    private $provider;

    public function __construct(SecurityContext $context, AclProvider $provider) {
        $this->context = $context;
        $this->provider = $provider;
    }

    public function setOwnerForEntity($entity) {

        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $acl = $this->provider->createAcl($objectIdentity);

        $securityIdentity = UserSecurityIdentity::fromAccount(
                        $this->context->getToken()->getUser()
        );

        $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
        $this->provider->updateAcl($acl);
    }

    public function setAuthorForEntity($entity) {

        $objectIdentity = ObjectIdentity::fromDomainObject($entity);
        $acl = $this->provider->createAcl($objectIdentity);

        $securityIdentity = UserSecurityIdentity::fromAccount(
                        $this->context->getToken()->getUser()
        );

        $maskBuilder = new MaskBuilder();

        $maskBuilder->add(MaskBuilder::MASK_EDIT);
        $maskBuilder->add(MaskBuilder::MASK_VIEW);
        $maskBuilder->add(MaskBuilder::MASK_DELETE);

        $acl->insertObjectAce($securityIdentity, $maskBuilder->get());
        $this->provider->updateAcl($acl);
    }

    public function removeAuthorFromEntity($entity) {

        $acl = $this->provider->findAcl(ObjectIdentity::fromDomainObject($entity));
        $securityId = UserSecurityIdentity::fromAccount($this->context->getToken()->getUser());

        foreach ($acl->getObjectAces() as $ace) {
            if ($ace->getSecurityIdentity()->equals($securityId)) {

                $maskBuilder = new MaskBuilder($ace->getMask());
                $maskBuilder->remove(MaskBuilder::MASK_EDIT);

                $ace->setMask($maskBuilder->get());
            }
        }
        $this->provider->updateAcl($acl);
    }

}
